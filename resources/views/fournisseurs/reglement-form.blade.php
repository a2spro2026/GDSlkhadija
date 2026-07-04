@extends('layouts.app')

@section('title', $isEdit ? 'Modifier règlement' : 'Nouveau règlement')
@section('page-title', $isEdit ? 'Modifier règlement' : 'Nouveau règlement')
@section('page-subtitle', 'Saisie et affectation aux bons d\'achat')

@push('styles')
<style>
    .reg-page { display: flex; flex-direction: column; gap: 0.75rem; max-width: 100%; }
    .reg-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(15,23,42,0.06); overflow: hidden; }
    .reg-head { padding: 0.65rem 1rem; background: linear-gradient(90deg, #071A35, #0F4C81); color: #fff; display: flex; justify-content: space-between; align-items: center; }
    .reg-head h2 { margin: 0; font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 600; }
    .reg-body { padding: 0.85rem 1rem; }
    .reg-row { display: flex; align-items: flex-end; gap: 0.4rem; flex-wrap: nowrap; overflow-x: auto; }
    .reg-fields { display: flex; flex: 1; gap: 0.4rem; align-items: flex-end; min-width: 0; }
    .reg-field { flex: 1; min-width: 0; }
    .reg-field label { display: flex; align-items: center; gap: 0.25rem; font-size: 0.56rem; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 0.28rem; white-space: nowrap; }
    .reg-field label i { color: #f59e0b; font-size: 0.6rem; }
    .reg-field input, .reg-field select { width: 100%; padding: 0.4rem 0.45rem; border: 1px solid #e2e8f0; border-radius: 0.4rem; font-size: 0.76rem; background: #f8fafc; }
    .reg-field input:focus, .reg-field select:focus { outline: none; border-color: #2563EB; background: #fff; box-shadow: 0 0 0 2px rgba(37,99,235,0.12); }
    .reg-field input.is-disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; }
    .solde-badge { font-size: 0.72rem; font-weight: 700; color: #fbbf24; background: rgba(255,255,255,0.12); padding: 0.3rem 0.65rem; border-radius: 999px; }
    .btn-reg { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.42rem 0.8rem; font-size: 0.74rem; font-weight: 600; border-radius: 0.4rem; border: 0; cursor: pointer; white-space: nowrap; text-decoration: none; }
    .btn-valid { background: linear-gradient(135deg, #2563EB, #1d4ed8); color: #fff; }
    .btn-back { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .reg-subhead { padding: 0.7rem 1rem; background: #fafbfc; border-bottom: 1px solid #e2e8f0; }
    .reg-subhead h3 { margin: 0; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; color: #071A35; }
    .reg-table-wrap { overflow: auto; }
    .reg-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; min-width: 700px; }
    .reg-table thead th { padding: 0.7rem 0.8rem; text-align: left; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; color: #e2e8f0; background: #071A35; border-bottom: 2px solid #f59e0b; }
    .reg-table tbody td { padding: 0.65rem 0.8rem; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .reg-table tbody tr:hover { background: #fffbeb; }
    .reg-table tbody tr.is-hidden { display: none; }
    .cell-paye.is-paid { color: #059669; font-weight: 700; }
    .cell-solde.is-due { color: #dc2626; font-weight: 700; }
    .cell-solde.is-zero { color: #64748b; }
</style>
@endpush

@section('content')
@php
    $reg = $reglement ?? null;
    $selectedBons = collect(old('bons', $reg ? $reg->bonsAchats->map(fn($b) => ['bon_achat_id' => $b->id, 'montant_affecte' => $b->pivot->montant_affecte])->all() : []));
    $selectedBonIds = $selectedBons->pluck('bon_achat_id')->map(fn($id) => (string) $id)->all();
@endphp

<div class="reg-page">
    <form id="regForm" method="POST" action="{{ $isEdit ? route('fournisseurs.reglement.update', $reg) : route('fournisseurs.reglement.store') }}" autocomplete="off">
        @csrf
        @if($isEdit) @method('PUT') @endif
        <div id="bonsHidden"></div>

        <div class="reg-card">
            <div class="reg-head">
                <h2><i class="fa-solid fa-money-check-dollar mr-2"></i>{{ $isEdit ? 'Modification règlement' : 'Saisie règlement' }}</h2>
                <span class="solde-badge" id="soldeBadge">Solde : —</span>
            </div>
            <div class="reg-body">
                <div class="reg-row">
                    <div class="reg-fields">
                        <div class="reg-field" style="flex:0.8;min-width:7rem">
                            <label><i class="fa-solid fa-calendar"></i> Date</label>
                            <input type="date" name="date_reglement" id="date_reglement"
                                value="{{ old('date_reglement', $reg?->date_reglement?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                        </div>
                        <div class="reg-field" style="flex:0.9;min-width:7.5rem">
                            <label><i class="fa-solid fa-barcode"></i> Réf</label>
                            <input type="text" name="reference" id="reference"
                                value="{{ old('reference', $reg?->reference ?? $nextReference) }}" readonly required>
                        </div>
                        <div class="reg-field" style="flex:1.2;min-width:9rem">
                            <label><i class="fa-solid fa-truck-field"></i> Fournisseur</label>
                            <select name="fournisseur_id" id="fournisseur_id" required>
                                <option value="">— Sélectionner —</option>
                                @foreach($fournisseurs as $f)
                                    <option value="{{ $f->id }}" @selected(old('fournisseur_id', $reg?->fournisseur_id) == $f->id)>{{ $f->raison_sociale }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="reg-field" style="flex:0.7;min-width:5.5rem">
                            <label><i class="fa-solid fa-tag"></i> Type Règl</label>
                            <select name="type_reglement" id="type_reglement" required>
                                @foreach($typeLabels as $k => $lbl)
                                    <option value="{{ $k }}" @selected(old('type_reglement', $reg?->type_reglement ?? 'esp') === $k)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="reg-field" style="flex:0.6;min-width:4.5rem">
                            <label><i class="fa-solid fa-hashtag"></i> N°</label>
                            <input type="text" name="numero" id="numero" value="{{ old('numero', $reg?->numero) }}" placeholder="N°">
                        </div>
                        <div class="reg-field" style="flex:0.8;min-width:6rem">
                            <label><i class="fa-solid fa-building-columns"></i> Bnq</label>
                            <input type="text" name="banque" id="banque" value="{{ old('banque', $reg?->banque) }}" placeholder="Banque">
                        </div>
                        <div class="reg-field" style="flex:0.7;min-width:5.5rem">
                            <label><i class="fa-solid fa-coins"></i> Mnt Règl</label>
                            <input type="number" name="montant" id="montant" min="0.01" step="0.01"
                                value="{{ old('montant', $isEdit ? $reg?->montant : '') }}"
                                placeholder="0,00" autocomplete="off" required>
                        </div>
                        <div class="reg-field" style="flex:0.9;min-width:7rem">
                            <label><i class="fa-solid fa-user"></i> Nom Tiré</label>
                            <input type="text" name="nom_tire" id="nom_tire" value="{{ old('nom_tire', $reg?->nom_tire) }}" placeholder="Nom">
                        </div>
                        <div class="reg-field" style="flex:0.8;min-width:7rem">
                            <label><i class="fa-solid fa-calendar-check"></i> Date Décaiss</label>
                            <input type="date" name="date_decaissement" id="date_decaissement"
                                value="{{ old('date_decaissement', $reg?->date_decaissement?->format('Y-m-d')) }}">
                        </div>
                        <div class="reg-field" style="flex:0.7;min-width:6rem">
                            <label><i class="fa-solid fa-circle-dot"></i> Statut</label>
                            <select name="statut" id="statut">
                                @foreach($statutLabels as $k => $lbl)
                                    <option value="{{ $k }}" @selected(old('statut', $reg?->statut ?? 'paye') === $k)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="display:flex;gap:0.35rem;flex-shrink:0">
                        <a href="{{ route('fournisseurs.reglement.index') }}" class="btn-reg btn-back"><i class="fa-solid fa-arrow-left"></i> Retour</a>
                        <button type="submit" class="btn-reg btn-valid"><i class="fa-solid fa-circle-check"></i> Valider</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="reg-card">
        <div class="reg-subhead">
            <h3><i class="fa-solid fa-clock text-amber-500 mr-1"></i> Commandes en attente de paiement</h3>
        </div>
        <div class="reg-table-wrap">
            <table class="reg-table" id="attenteTable">
                <thead>
                    <tr>
                        <th style="width:36px"></th>
                        <th>N° Bon</th>
                        <th>Date Commande</th>
                        <th>Fournisseur</th>
                        <th>Montant Cmd</th>
                        <th>Montant payé</th>
                        <th>Solde</th>
                    </tr>
                </thead>
                <tbody id="attenteBody">
                    @forelse($bonsEnAttente as $bon)
                        @php
                            $payeInit = $bon->montantPaye();
                            $soldeInit = $bon->solde();
                        @endphp
                        <tr class="attente-row" data-fournisseur="{{ $bon->fournisseur_id }}"
                            data-total="{{ $bon->total }}"
                            data-paye-init="{{ $payeInit }}"
                            data-solde-init="{{ $soldeInit }}">
                            <td>
                                <input type="checkbox" class="bon-check" value="{{ $bon->id }}"
                                    data-solde="{{ $soldeInit }}"
                                    @checked(in_array((string) $bon->id, $selectedBonIds, true))>
                            </td>
                            <td class="font-semibold">{{ $bon->numero_bon }}</td>
                            <td>{{ $bon->date_bon->format('d/m/Y') }}</td>
                            <td>{{ $bon->fournisseur->raison_sociale ?? '—' }}</td>
                            <td class="cell-cmd">{{ number_format($bon->total, 2, ',', ' ') }}</td>
                            <td class="cell-paye">{{ number_format($payeInit, 2, ',', ' ') }}</td>
                            <td class="cell-solde {{ $soldeInit > 0 ? 'is-due' : 'is-zero' }}">{{ number_format($soldeInit, 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-8 text-slate-400">Aucune commande en attente</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const isCreate = @json(! $isEdit);
    const nextReference = @json($nextReference);
    const soldeUrl = @json(url('fournisseurs/reglement/fournisseur'));

    const els = {
        fournisseur: document.getElementById('fournisseur_id'),
        type: document.getElementById('type_reglement'),
        numero: document.getElementById('numero'),
        banque: document.getElementById('banque'),
        montant: document.getElementById('montant'),
        statut: document.getElementById('statut'),
        soldeBadge: document.getElementById('soldeBadge'),
        bonsHidden: document.getElementById('bonsHidden'),
    };

    function formatMoney(value) {
        return Number(value).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function toggleEspFields() {
        const esp = els.type.value === 'esp';
        [els.numero, els.banque].forEach((el) => {
            el.disabled = esp;
            el.classList.toggle('is-disabled', esp);
            if (esp) el.value = '';
        });
    }
    els.type.addEventListener('change', toggleEspFields);
    toggleEspFields();

    function filterAttenteRows() {
        const fid = els.fournisseur.value;
        document.querySelectorAll('.attente-row').forEach((row) => {
            row.classList.toggle('is-hidden', fid && row.dataset.fournisseur !== fid);
        });
    }

    function refreshRowAmounts(fromCheckbox = false) {
        const countsAsPaid = els.statut.value === 'paye';
        let remaining = parseFloat(els.montant.value) || 0;
        const checked = [];

        if (fromCheckbox) {
            let sumSolde = 0;
            document.querySelectorAll('.bon-check:checked').forEach((cb) => {
                sumSolde += parseFloat(cb.dataset.solde) || 0;
            });
            if (sumSolde > 0) {
                els.montant.value = sumSolde.toFixed(2);
                remaining = sumSolde;
            } else if (!document.querySelector('.bon-check:checked')) {
                els.montant.value = '';
                remaining = 0;
            }
        }

        document.querySelectorAll('.attente-row').forEach((row) => {
            const cb = row.querySelector('.bon-check');
            const payeInit = parseFloat(row.dataset.payeInit) || 0;
            const soldeInit = parseFloat(row.dataset.soldeInit) || 0;
            const payeCell = row.querySelector('.cell-paye');
            const soldeCell = row.querySelector('.cell-solde');

            let affecte = 0;
            if (cb.checked && remaining > 0 && !row.classList.contains('is-hidden')) {
                affecte = Math.min(remaining, soldeInit);
                remaining = Math.round((remaining - affecte) * 100) / 100;
                if (affecte > 0) {
                    checked.push({ bon_achat_id: cb.value, montant_affecte: affecte });
                }
            }

            const payeDisplay = payeInit + (countsAsPaid ? affecte : 0);
            const soldeDisplay = Math.max(0, Math.round((soldeInit - (countsAsPaid ? affecte : 0)) * 100) / 100);

            payeCell.textContent = formatMoney(payeDisplay);
            soldeCell.textContent = formatMoney(soldeDisplay);

            payeCell.classList.toggle('is-paid', countsAsPaid && soldeDisplay <= 0 && payeDisplay > 0);
            soldeCell.classList.toggle('is-due', soldeDisplay > 0);
            soldeCell.classList.toggle('is-zero', soldeDisplay <= 0);
        });

        els.bonsHidden.innerHTML = '';
        checked.forEach((b, i) => {
            ['bon_achat_id', 'montant_affecte'].forEach((k) => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = `bons[${i}][${k}]`;
                inp.value = b[k];
                els.bonsHidden.appendChild(inp);
            });
        });
    }

    function updateMontantFromChecks(fromCheckbox = true) {
        refreshRowAmounts(fromCheckbox);
    }

    function resetCreateForm() {
        els.fournisseur.value = '';
        els.type.value = 'esp';
        els.numero.value = '';
        els.banque.value = '';
        els.montant.value = '';
        document.getElementById('nom_tire').value = '';
        document.getElementById('date_decaissement').value = '';
        els.statut.value = 'paye';
        document.getElementById('date_reglement').value = new Date().toISOString().slice(0, 10);
        document.getElementById('reference').value = nextReference;
        document.querySelectorAll('.bon-check').forEach((c) => { c.checked = false; });
        toggleEspFields();
        filterAttenteRows();
        els.soldeBadge.textContent = 'Solde : —';
        document.querySelectorAll('.attente-row').forEach((row) => {
            const payeInit = parseFloat(row.dataset.payeInit) || 0;
            const soldeInit = parseFloat(row.dataset.soldeInit) || 0;
            row.querySelector('.cell-paye').textContent = formatMoney(payeInit);
            const soldeCell = row.querySelector('.cell-solde');
            soldeCell.textContent = formatMoney(soldeInit);
            soldeCell.classList.toggle('is-due', soldeInit > 0);
            soldeCell.classList.toggle('is-zero', soldeInit <= 0);
            row.querySelector('.cell-paye').classList.remove('is-paid');
        });
        els.bonsHidden.innerHTML = '';
    }

    function initForm() {
        if (isCreate && !@json((bool) old('montant'))) {
            resetCreateForm();
        } else {
            filterAttenteRows();
            updateMontantFromChecks(false);
            refreshSolde();
        }
    }

    async function refreshSolde() {
        const id = els.fournisseur.value;
        if (!id) { els.soldeBadge.textContent = 'Solde : —'; return; }
        try {
            const res = await fetch(`${soldeUrl}/${id}/solde`);
            const data = await res.json();
            els.soldeBadge.textContent = 'Solde : ' + Number(data.solde_total).toFixed(2) + ' DH';
        } catch { els.soldeBadge.textContent = 'Solde : —'; }
    }

    els.fournisseur.addEventListener('change', () => {
        filterAttenteRows();
        document.querySelectorAll('.bon-check').forEach((c) => { c.checked = false; });
        els.montant.value = '';
        updateMontantFromChecks(false);
        refreshSolde();
    });

    document.getElementById('attenteBody').addEventListener('change', (e) => {
        if (e.target.classList.contains('bon-check')) updateMontantFromChecks(true);
    });

    els.montant.addEventListener('input', () => updateMontantFromChecks(false));
    els.statut.addEventListener('change', () => updateMontantFromChecks(false));

    initForm();

    window.addEventListener('pageshow', (e) => {
        if (isCreate && e.persisted) {
            resetCreateForm();
        }
    });

    document.getElementById('regForm').addEventListener('submit', (e) => {
        const hasChecked = document.querySelector('.bon-check:checked');
        if (!hasChecked && els.fournisseur.value && parseFloat(els.montant.value) > 0) {
            let remaining = parseFloat(els.montant.value);
            document.querySelectorAll('.attente-row:not(.is-hidden) .bon-check').forEach((cb) => {
                if (remaining <= 0) return;
                const solde = parseFloat(cb.dataset.solde) || 0;
                if (solde > 0) {
                    cb.checked = true;
                    remaining -= solde;
                }
            });
            updateMontantFromChecks(false);
        }
    });
</script>
@endsection
