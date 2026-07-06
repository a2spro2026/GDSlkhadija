@extends('layouts.app')

@section('title', "Bon d'achats")
@section('page-title', "Bon d'achats")
@section('page-subtitle', 'Saisie et validation des bons d\'achat fournisseurs')

@push('styles')
<style>
    .ba-page { max-width: 100%; width: 100%; display: flex; flex-direction: column; gap: 0.75rem; }

    .app-main:has(.ba-page) .page-container { max-width: 100%; }

    .ba-form-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        width: 100%;
    }
    .ba-form-head {
        padding: 0.65rem 1rem;
        background: linear-gradient(90deg, #071A35, #0F4C81);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ba-form-head h2 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .ba-total-badge {
        font-size: 0.72rem;
        font-weight: 700;
        color: #fbbf24;
        background: rgba(255,255,255,0.12);
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
    }
    .ba-form-body { padding: 1rem 1.25rem; width: 100%; }

    .ba-form-row {
        display: flex;
        align-items: flex-end;
        gap: 0.4rem;
        flex-wrap: nowrap;
        width: 100%;
        overflow-x: auto;
    }
    .ba-fields--line {
        display: flex;
        flex: 1;
        gap: 0.35rem;
        align-items: flex-end;
        flex-wrap: nowrap;
        min-width: 0;
        width: 100%;
    }

    .ba-field { flex: 1 1 auto; min-width: 0; }
    .ba-field--date { flex: 0 0 7.25rem; min-width: 7.25rem; }
    .ba-field--num { flex: 0 0 7.75rem; min-width: 7.75rem; }
    .ba-field--fourn { flex: 0 0 7.5rem; min-width: 7.5rem; max-width: 7.5rem; }
    .ba-field--ref { flex: 0 0 5.75rem; min-width: 5.75rem; }
    .ba-field--desig { flex: 1 1 8rem; min-width: 6rem; }
    .ba-field--stock { flex: 0 0 5.5rem; min-width: 5.5rem; }
    .ba-field--qte { flex: 0 0 4.5rem; min-width: 4.5rem; }
    .ba-field--mesur { flex: 0 0 4.75rem; min-width: 4.75rem; }
    .ba-field--prix { flex: 0 0 5.25rem; min-width: 5.25rem; }
    .ba-field--st { flex: 0 0 5.75rem; min-width: 5.75rem; }

    .ba-field label {
        display: flex; align-items: center; gap: 0.2rem;
        font-size: 0.55rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.03em; color: #475569; margin-bottom: 0.28rem; white-space: nowrap;
    }
    .ba-field label i { color: #f59e0b; font-size: 0.58rem; }
    .ba-field input, .ba-field select {
        width: 100%; padding: 0.45rem 0.5rem; border: 1px solid #e2e8f0;
        border-radius: 0.4rem; font-size: 0.76rem; background: #f8fafc;
    }
    .ba-field input:focus, .ba-field select:focus {
        outline: none; border-color: #2563EB; background: #fff;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12);
    }
    .ba-field input[readonly] { background: #f1f5f9; color: #0f172a; font-weight: 600; }

    .ba-actions { display: flex; flex-shrink: 0; gap: 0.3rem; align-items: center; }
    .btn-ba {
        display: inline-flex; align-items: center; gap: 0.25rem;
        padding: 0.45rem 0.7rem; font-size: 0.72rem; font-weight: 600;
        border-radius: 0.4rem; border: 0; cursor: pointer; white-space: nowrap;
    }
    .btn-add { background: linear-gradient(135deg, #06b6d4, #0891b2); color: #fff; }
    .btn-valid { background: linear-gradient(135deg, #2563EB, #1d4ed8); color: #fff; }
    .btn-cancel { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; display: none; }

    .ba-display-zone {
        margin-top: 0.65rem;
        padding-top: 0.65rem;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        max-height: 200px;
        overflow-y: auto;
    }
    .ba-display-empty {
        text-align: center;
        font-size: 0.72rem;
        color: #94a3b8;
        padding: 0.5rem;
    }
    .ba-display-row {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.45rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.45rem;
        font-size: 0.74rem;
    }
    .ba-display-row span {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #334155;
    }
    .ba-display-row .d-ref { flex: 0.8; }
    .ba-display-row .d-desig { flex: 1.6; font-weight: 600; }
    .ba-display-row .d-num { flex: 0.5; text-align: right; }
    .ba-row-edit,
    .ba-row-del {
        width: 1.6rem; height: 1.6rem; border-radius: 0.35rem;
        cursor: pointer; display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.65rem; flex-shrink: 0; border: 1px solid;
    }
    .ba-row-edit {
        border-color: #bfdbfe; background: #eff6ff; color: #2563EB;
    }
    .ba-row-edit:hover { background: #dbeafe; }
    .ba-row-del {
        border-color: #fecaca; background: #fef2f2; color: #dc2626;
    }
    .ba-row-del:hover { background: #fee2e2; }

    .edit-badge {
        display: none; font-size: 0.68rem; font-weight: 600; color: #1d4ed8;
        background: #dbeafe; padding: 0.3rem 0.6rem; border-radius: 999px; margin-bottom: 0.5rem;
    }
    .edit-badge.is-visible { display: inline-flex; align-items: center; gap: 0.3rem; }

    .ba-table-card {
        background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06); overflow: hidden;
        flex: 1; display: flex; flex-direction: column;
    }
    .ba-table-head {
        padding: 0.85rem 1.1rem; background: #fafbfc; border-bottom: 1px solid #e2e8f0;
    }
    .ba-table-head h3 {
        margin: 0; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 600; color: #071A35;
    }
    .ba-hint { font-size: 0.68rem; color: #94a3b8; margin: 0.2rem 0 0; }
    .ba-table-wrap { overflow: auto; flex: 1; }
    .ba-table { width: 100%; border-collapse: collapse; font-size: 0.8125rem; min-width: 800px; }
    .ba-table thead th {
        padding: 0.8rem 0.9rem; text-align: left; font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; color: #e2e8f0; background: #071A35; border-bottom: 2px solid #f59e0b;
    }
    .ba-table tbody td { padding: 0.75rem 0.9rem; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .ba-table tbody tr.bon-row { cursor: pointer; transition: background 0.15s; }
    .ba-table tbody tr.bon-row:hover { background: #fffbeb; }
    .ba-table tbody tr.is-editing { background: #eff6ff; outline: 2px solid #2563EB; outline-offset: -2px; }

    .row-actions { display: flex; gap: 0.3rem; justify-content: flex-end; }
    .row-btn {
        width: 2rem; height: 2rem; border-radius: 0.4rem; border: 1px solid #e2e8f0;
        background: #fff; color: #475569; display: inline-flex; align-items: center;
        justify-content: center; font-size: 0.72rem; cursor: pointer; text-decoration: none;
    }
    .row-btn.edit { color: #2563EB; border-color: #bfdbfe; background: #eff6ff; }
    .row-btn.print { color: #7c3aed; border-color: #ddd6fe; background: #f5f3ff; }
    .row-btn.pdf { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
    .row-btn.delete { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
    .row-actions form { display: inline-flex; margin: 0; }
</style>
@endpush

@section('content')
<div class="ba-page">
    <form id="bonForm" method="POST" action="{{ route('fournisseurs.bons-achats.store') }}">
        @csrf
        <input type="hidden" name="_method" id="formMethod" value="POST">
        <div id="lignesHidden"></div>

        <div class="ba-form-card">
            <div class="ba-form-head">
                <h2><i class="fa-solid fa-file-invoice mr-2"></i>Saisie bon d'achats</h2>
                <span class="ba-total-badge" id="totalBadge">Total : 0.00 DH</span>
            </div>
            <div class="ba-form-body">
                <div class="edit-badge" id="editBadge"><i class="fa-solid fa-pen"></i> Mode modification</div>

                <div class="ba-form-row">
                    <div class="ba-fields--line">
                        <div class="ba-field ba-field--date">
                            <label for="date_bon"><i class="fa-solid fa-calendar"></i> Date</label>
                            <input type="date" id="date_bon" name="date_bon" value="{{ old('date_bon', date('Y-m-d')) }}" required>
                        </div>
                        <div class="ba-field ba-field--num">
                            <label for="numero_bon"><i class="fa-solid fa-hashtag"></i> N° bon</label>
                            <input type="text" id="numero_bon" name="numero_bon" value="{{ old('numero_bon', $nextNumero) }}" required>
                        </div>
                        <div class="ba-field ba-field--fourn">
                            <label for="fournisseur_id"><i class="fa-solid fa-truck-field"></i> Fourn.</label>
                            <select id="fournisseur_id" name="fournisseur_id" required>
                                <option value="">—</option>
                                @foreach($fournisseurs as $f)
                                    <option value="{{ $f->id }}" @selected(old('fournisseur_id') == $f->id)>{{ $f->raison_sociale }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ba-field ba-field--ref">
                            <label for="ligne_ref"><i class="fa-solid fa-barcode"></i> Réf</label>
                            <input type="text" id="ligne_ref" placeholder="REF">
                        </div>
                        <div class="ba-field ba-field--desig">
                            <label for="ligne_designation"><i class="fa-solid fa-box"></i> Désignation</label>
                            <input type="text" id="ligne_designation" placeholder="Article...">
                        </div>
                        <div class="ba-field ba-field--stock">
                            <label for="ligne_stock"><i class="fa-solid fa-warehouse"></i> Stk Init</label>
                            <input type="number" id="ligne_stock" min="0" step="0.01" value="0">
                        </div>
                        <div class="ba-field ba-field--qte">
                            <label for="ligne_qte"><i class="fa-solid fa-cubes"></i> Qté</label>
                            <input type="number" id="ligne_qte" min="0.01" step="0.01" value="1">
                        </div>
                        <div class="ba-field ba-field--mesur">
                            <label for="ligne_mesure"><i class="fa-solid fa-ruler"></i> Mesur</label>
                            <input type="text" id="ligne_mesure" placeholder="U">
                        </div>
                        <div class="ba-field ba-field--prix">
                            <label for="ligne_prix"><i class="fa-solid fa-coins"></i> Prix U</label>
                            <input type="number" id="ligne_prix" min="0" step="0.01" value="0">
                        </div>
                        <div class="ba-field ba-field--st">
                            <label for="ligne_sous_total"><i class="fa-solid fa-calculator"></i> S-total</label>
                            <input type="text" id="ligne_sous_total" readonly value="0.00">
                        </div>
                    </div>
                    <div class="ba-actions">
                        <button type="button" class="btn-ba btn-cancel" id="btnCancel"><i class="fa-solid fa-xmark"></i></button>
                        <button type="button" class="btn-ba btn-add" id="btnAjouter"><i class="fa-solid fa-plus"></i></button>
                        <button type="submit" class="btn-ba btn-valid" id="btnValider"><i class="fa-solid fa-circle-check"></i> Valider</button>
                    </div>
                </div>

                <div class="ba-display-zone" id="lignesDisplay">
                    <div class="ba-display-empty" id="displayEmpty">Aucune ligne ajoutée</div>
                </div>

                @error('lignes')<p class="text-xs text-red-600 mt-2 m-0">{{ $message }}</p>@enderror
            </div>
        </div>
    </form>

    <div class="ba-table-card">
        <div class="ba-table-head">
            <h3><i class="fa-solid fa-table-list text-amber-500 mr-1"></i> Liste des bons d'achats</h3>
            <p class="ba-hint">Double-cliquez sur une ligne pour modifier</p>
        </div>
        <div class="ba-table-wrap">
            <table class="ba-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N° bon</th>
                        <th>Fournisseur</th>
                        <th>Réf</th>
                        <th>Désignation</th>
                        <th>Total</th>
                        <th style="text-align:right;width:150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bonsAchats as $bon)
                        <tr class="bon-row"
                            data-id="{{ $bon->id }}"
                            data-date="{{ $bon->date_bon->format('Y-m-d') }}"
                            data-numero="{{ $bon->numero_bon }}"
                            data-fournisseur="{{ $bon->fournisseur_id }}"
                            data-lignes="{{ $bon->lignes->toJson() }}"
                            data-update-url="{{ route('fournisseurs.bons-achats.update', $bon) }}">
                            <td>{{ $bon->date_bon->format('d/m/Y') }}</td>
                            <td class="font-semibold">{{ $bon->numero_bon }}</td>
                            <td>{{ $bon->fournisseur->raison_sociale ?? '—' }}</td>
                            <td>{{ $bon->lignes->pluck('reference')->filter()->unique()->implode(', ') ?: '—' }}</td>
                            <td>{{ $bon->lignes->pluck('designation')->implode(', ') ?: '—' }}</td>
                            <td class="font-semibold text-blue-700">{{ number_format($bon->total, 2, ',', ' ') }} DH</td>
                            <td>
                                <div class="row-actions" onclick="event.stopPropagation()">
                                    <button type="button" class="row-btn edit btn-edit-bon" title="Modifier"><i class="fa-solid fa-pen"></i></button>
                                    <a href="{{ route('fournisseurs.bons-achats.print', $bon) }}" target="_blank" class="row-btn print" title="Imprimer"><i class="fa-solid fa-print"></i></a>
                                    <a href="{{ route('fournisseurs.bons-achats.export-pdf', $bon) }}" target="_blank" class="row-btn pdf" title="PDF"><i class="fa-solid fa-file-pdf"></i></a>
                                    <form method="POST" action="{{ route('fournisseurs.bons-achats.destroy', $bon) }}" onsubmit="return confirm('Supprimer ce bon d\'achat ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="row-btn delete" title="Supprimer"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">Aucun bon d'achat enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const lignes = [];
    const fmt = (n) => Number(n).toFixed(2);
    const storeUrl = @json(route('fournisseurs.bons-achats.store'));

    const els = {
        form: document.getElementById('bonForm'),
        formMethod: document.getElementById('formMethod'),
        editBadge: document.getElementById('editBadge'),
        btnCancel: document.getElementById('btnCancel'),
        ref: document.getElementById('ligne_ref'),
        designation: document.getElementById('ligne_designation'),
        stock: document.getElementById('ligne_stock'),
        qte: document.getElementById('ligne_qte'),
        mesure: document.getElementById('ligne_mesure'),
        prix: document.getElementById('ligne_prix'),
        sousTotal: document.getElementById('ligne_sous_total'),
        display: document.getElementById('lignesDisplay'),
        displayEmpty: document.getElementById('displayEmpty'),
        hidden: document.getElementById('lignesHidden'),
        totalBadge: document.getElementById('totalBadge'),
        date: document.getElementById('date_bon'),
        numero: document.getElementById('numero_bon'),
        fournisseur: document.getElementById('fournisseur_id'),
    };

    function calcSousTotal() {
        const st = (parseFloat(els.qte.value) || 0) * (parseFloat(els.prix.value) || 0);
        els.sousTotal.value = fmt(st);
        return st;
    }
    els.qte.addEventListener('input', calcSousTotal);
    els.prix.addEventListener('input', calcSousTotal);

    function renderLignes() {
        els.display.querySelectorAll('.ba-display-row').forEach((r) => r.remove());
        els.displayEmpty.style.display = lignes.length ? 'none' : 'block';

        lignes.forEach((l, i) => {
            const row = document.createElement('div');
            row.className = 'ba-display-row';
            row.innerHTML = `
                <span class="d-ref" title="Réf">${l.reference || '—'}</span>
                <span class="d-desig" title="Désignation">${l.designation}</span>
                <span class="d-num" title="Stock init.">${fmt(l.stock_initial || 0)}</span>
                <span class="d-num">${fmt(l.quantite)} ${l.mesure || ''}</span>
                <span class="d-num">${fmt(l.prix_unitaire)}</span>
                <span class="d-num" style="font-weight:700;color:#1d4ed8">${fmt(l.sous_total)}</span>
                <button type="button" class="ba-row-edit" data-i="${i}" title="Modifier"><i class="fa-solid fa-pen"></i></button>
                <button type="button" class="ba-row-del" data-i="${i}" title="Supprimer"><i class="fa-solid fa-trash-can"></i></button>
            `;
            els.display.appendChild(row);
        });

        const total = lignes.reduce((s, l) => s + l.sous_total, 0);
        els.totalBadge.textContent = 'Total : ' + fmt(total) + ' DH';

        els.hidden.innerHTML = '';
        lignes.forEach((l, i) => {
            ['reference', 'designation', 'mesure', 'stock_initial', 'quantite', 'prix_unitaire', 'sous_total'].forEach((k) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `lignes[${i}][${k}]`;
                input.value = l[k] ?? '';
                els.hidden.appendChild(input);
            });
        });
    }

    function resetForm() {
        lignes.length = 0;
        els.form.action = storeUrl;
        els.formMethod.value = 'POST';
        els.date.value = new Date().toISOString().slice(0, 10);
        els.numero.value = @json($nextNumero);
        els.fournisseur.value = '';
        els.ref.value = '';
        els.designation.value = '';
        els.stock.value = '0';
        els.qte.value = '1';
        els.mesure.value = '';
        els.prix.value = '0';
        calcSousTotal();
        els.editBadge.classList.remove('is-visible');
        els.btnCancel.style.display = 'none';
        document.querySelectorAll('.bon-row').forEach((r) => r.classList.remove('is-editing'));
        renderLignes();
    }

    function loadBon(row) {
        els.date.value = row.dataset.date;
        els.numero.value = row.dataset.numero;
        els.fournisseur.value = row.dataset.fournisseur;

        lignes.length = 0;
        JSON.parse(row.dataset.lignes).forEach((l) => {
            lignes.push({
                reference: l.reference || '',
                designation: l.designation,
                mesure: l.mesure || '',
                stock_initial: parseFloat(l.stock_initial) || 0,
                quantite: parseFloat(l.quantite),
                prix_unitaire: parseFloat(l.prix_unitaire),
                sous_total: parseFloat(l.sous_total),
            });
        });

        els.form.action = row.dataset.updateUrl;
        els.formMethod.value = 'PUT';
        els.editBadge.classList.add('is-visible');
        els.btnCancel.style.display = 'inline-flex';
        document.querySelectorAll('.bon-row').forEach((r) => r.classList.remove('is-editing'));
        row.classList.add('is-editing');
        renderLignes();
        els.form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    document.getElementById('btnAjouter').addEventListener('click', () => {
        const designation = els.designation.value.trim();
        if (!designation) { els.designation.focus(); return; }
        const quantite = parseFloat(els.qte.value) || 0;
        const prix_unitaire = parseFloat(els.prix.value) || 0;
        if (quantite <= 0) { els.qte.focus(); return; }

        lignes.push({
            reference: els.ref.value.trim(),
            designation,
            mesure: els.mesure.value.trim(),
            stock_initial: parseFloat(els.stock.value) || 0,
            quantite,
            prix_unitaire,
            sous_total: quantite * prix_unitaire,
        });
        els.ref.value = '';
        els.designation.value = '';
        els.stock.value = '0';
        els.qte.value = '1';
        els.mesure.value = '';
        els.prix.value = '0';
        calcSousTotal();
        els.designation.focus();
        renderLignes();
    });

    els.display.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.ba-row-edit');
        if (editBtn) {
            const i = parseInt(editBtn.dataset.i, 10);
            const l = lignes[i];
            if (!l) return;
            els.ref.value = l.reference || '';
            els.designation.value = l.designation;
            els.stock.value = l.stock_initial || 0;
            els.mesure.value = l.mesure || '';
            els.qte.value = l.quantite;
            els.prix.value = l.prix_unitaire;
            calcSousTotal();
            lignes.splice(i, 1);
            renderLignes();
            els.designation.focus();
            return;
        }
        const delBtn = e.target.closest('.ba-row-del');
        if (!delBtn) return;
        lignes.splice(parseInt(delBtn.dataset.i, 10), 1);
        renderLignes();
    });

    els.form.addEventListener('submit', (e) => {
        if (lignes.length === 0) {
            e.preventDefault();
            alert('Ajoutez au moins une ligne avant de valider.');
        }
    });

    els.btnCancel.addEventListener('click', resetForm);

    document.querySelectorAll('.bon-row').forEach((row) => {
        row.addEventListener('dblclick', () => loadBon(row));
        row.querySelector('.btn-edit-bon')?.addEventListener('click', () => loadBon(row));
    });

    calcSousTotal();
    renderLignes();
</script>
@endsection
