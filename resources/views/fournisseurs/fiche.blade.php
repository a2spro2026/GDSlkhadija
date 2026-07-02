@extends('layouts.app')

@section('title', 'Fiche Fournisseur')
@section('page-title', 'Fiche Fournisseur')
@section('page-subtitle', 'Gestion des fiches fournisseurs')

@push('styles')
<style>
    .app-main:has(.fiche-page) {
        height: 100vh;
        overflow: hidden;
    }
    .app-main:has(.fiche-page) .page-body {
        flex: 1;
        min-height: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding-top: 1rem;
        padding-bottom: 0.5rem;
    }
    .app-main:has(.fiche-page) .page-container {
        flex: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        max-width: 100%;
    }
    .app-main:has(.fiche-page) .page-container > .mb-5 {
        flex-shrink: 0;
        margin-bottom: 0.75rem !important;
    }

    .fiche-page {
        max-width: 100%;
        margin: 0 auto;
        flex: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        overflow: hidden;
    }

    .fiche-form-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        flex-shrink: 0;
        z-index: 10;
    }
    .fiche-form-head {
        padding: 0.65rem 1rem;
        background: linear-gradient(90deg, #071A35, #0F4C81);
        color: #fff;
    }
    .fiche-form-head h2 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .fiche-form-body { padding: 0.85rem 1rem; }

    .fiche-form-row {
        display: flex;
        align-items: flex-end;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .fiche-fields {
        display: flex;
        flex: 1;
        gap: 0.45rem;
        align-items: flex-end;
        min-width: 0;
    }

    .fiche-field {
        flex: 1;
        min-width: 0;
    }
    .fiche-field label {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.58rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 0.3rem;
        white-space: nowrap;
    }
    .fiche-field label i { color: #f59e0b; font-size: 0.62rem; }
    .fiche-field input {
        width: 100%;
        padding: 0.4rem 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.4rem;
        font-size: 0.78rem;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .fiche-field input:focus {
        outline: none;
        border-color: #2563EB;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12);
    }

    .fiche-actions {
        display: flex;
        flex-shrink: 0;
        gap: 0.4rem;
        align-items: center;
        padding-bottom: 1px;
    }
    .btn-fiche {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.42rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.4rem;
        border: 0;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.15s, transform 0.15s;
        white-space: nowrap;
    }
    .btn-fiche:hover { transform: translateY(-1px); opacity: 0.95; }
    .btn-save {
        background: linear-gradient(135deg, #2563EB, #1d4ed8);
        color: #fff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-print {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #fff;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
    }
    .btn-cancel {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .fiche-table-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    .fiche-table-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding: 1.1rem 1.5rem;
        background: #fafbfc;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .fiche-table-head h3 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #071A35;
    }
    .fiche-hint {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0.25rem 0 0;
    }
    .fiche-table-wrap {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        overflow-x: auto;
    }
    .fiche-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9375rem;
        min-width: 100%;
        table-layout: fixed;
    }
    .fiche-table thead th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #e2e8f0;
        background: #071A35;
        border-bottom: 2px solid #f59e0b;
    }
    .fiche-table thead th:nth-child(1) { width: 22%; }
    .fiche-table thead th:nth-child(2) { width: 18%; }
    .fiche-table thead th:nth-child(3) { width: 14%; }
    .fiche-table thead th:nth-child(4) { width: 14%; }
    .fiche-table thead th:nth-child(5) { width: 22%; }
    .fiche-table thead th:nth-child(6) { width: 12%; }
    .fiche-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .fiche-table tbody tr {
        cursor: pointer;
        transition: background 0.15s;
    }
    .fiche-table tbody tr:hover { background: #fffbeb; }
    .fiche-table tbody tr.is-editing { background: #eff6ff; outline: 2px solid #2563EB; outline-offset: -2px; }

    .row-actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        justify-content: flex-end;
    }
    .row-actions form {
        display: inline-flex;
        margin: 0;
    }
    .row-btn {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.45rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
    }
    .row-btn:hover { transform: scale(1.05); }
    .row-btn.edit { color: #2563EB; border-color: #bfdbfe; background: #eff6ff; }
    .row-btn.print { color: #7c3aed; border-color: #ddd6fe; background: #f5f3ff; }
    .row-btn.pdf { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
    .row-btn.delete { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
    .row-btn.delete:hover { background: #fee2e2; border-color: #fca5a5; }

    .edit-mode-badge {
        display: none;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: #1d4ed8;
        background: #dbeafe;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        margin-bottom: 0.75rem;
    }
    .edit-mode-badge.is-visible { display: inline-flex; }
</style>
@endpush

@section('content')
<div class="fiche-page">
    <div class="fiche-form-card">
        <div class="fiche-form-head">
            <h2><i class="fa-solid fa-truck-field mr-2"></i>Saisie fournisseur</h2>
        </div>
        <form id="ficheForm" method="POST" action="{{ route('fournisseurs.fiche.store') }}" class="fiche-form-body">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="fournisseur_id" id="fournisseurId" value="">

            <div class="edit-mode-badge" id="editBadge">
                <i class="fa-solid fa-pen"></i> Mode modification
            </div>

            <div class="fiche-form-row">
                <div class="fiche-fields">
                    <div class="fiche-field">
                        <label for="raison_sociale"><i class="fa-solid fa-building"></i> Raison sociale</label>
                        <input type="text" id="raison_sociale" name="raison_sociale" value="{{ old('raison_sociale') }}" required>
                        @error('raison_sociale')<p class="text-xs text-red-600 mt-1 m-0">{{ $message }}</p>@enderror
                    </div>
                    <div class="fiche-field">
                        <label for="nom_responsable"><i class="fa-solid fa-user-tie"></i> Responsable</label>
                        <input type="text" id="nom_responsable" name="nom_responsable" value="{{ old('nom_responsable') }}" required>
                        @error('nom_responsable')<p class="text-xs text-red-600 mt-1 m-0">{{ $message }}</p>@enderror
                    </div>
                    <div class="fiche-field">
                        <label for="profil"><i class="fa-solid fa-id-badge"></i> Profil</label>
                        <input type="text" id="profil" name="profil" value="{{ old('profil') }}" placeholder="Grossiste...">
                        @error('profil')<p class="text-xs text-red-600 mt-1 m-0">{{ $message }}</p>@enderror
                    </div>
                    <div class="fiche-field">
                        <label for="contact"><i class="fa-solid fa-phone"></i> Contact</label>
                        <input type="text" id="contact" name="contact" value="{{ old('contact') }}" placeholder="06...">
                        @error('contact')<p class="text-xs text-red-600 mt-1 m-0">{{ $message }}</p>@enderror
                    </div>
                    <div class="fiche-field">
                        <label for="email"><i class="fa-solid fa-envelope"></i> E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@...">
                        @error('email')<p class="text-xs text-red-600 mt-1 m-0">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="fiche-actions">
                    <button type="button" class="btn-fiche btn-cancel" id="btnCancel" style="display:none">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <a href="{{ route('fournisseurs.fiche.print-all') }}" target="_blank" class="btn-fiche btn-print" title="Imprimer">
                        <i class="fa-solid fa-print"></i>
                    </a>
                    <button type="submit" class="btn-fiche btn-save" id="btnSave" title="Enregistrer">
                        <i class="fa-solid fa-floppy-disk"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="fiche-table-card">
        <div class="fiche-table-head">
            <div>
                <h3><i class="fa-solid fa-table-list text-amber-500 mr-1"></i> Liste des fournisseurs</h3>
                <p class="fiche-hint">Double-cliquez sur une ligne pour charger les données en modification</p>
            </div>
            <a href="{{ route('fournisseurs.fiche.export-pdf') }}" target="_blank" class="btn-fiche btn-print" style="font-size:0.75rem;padding:0.5rem 0.9rem">
                <i class="fa-solid fa-file-pdf"></i> Exporter PDF
            </a>
        </div>
        <div class="fiche-table-wrap">
            <table class="fiche-table" id="fournisseursTable">
                <thead>
                    <tr>
                        <th>Raison sociale</th>
                        <th>Nom responsable</th>
                        <th>Profil</th>
                        <th>Contact</th>
                        <th>E-mail</th>
                        <th style="text-align:right;width:150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fournisseurs as $f)
                        <tr class="fournisseur-row"
                            data-id="{{ $f->id }}"
                            data-raison="{{ $f->raison_sociale }}"
                            data-responsable="{{ $f->nom_responsable }}"
                            data-profil="{{ $f->profil }}"
                            data-contact="{{ $f->contact }}"
                            data-email="{{ $f->email }}"
                            data-update-url="{{ route('fournisseurs.fiche.update', $f) }}"
                            data-print-url="{{ route('fournisseurs.fiche.print', $f) }}">
                            <td class="font-semibold text-slate-800">{{ $f->raison_sociale }}</td>
                            <td>{{ $f->nom_responsable }}</td>
                            <td>{{ $f->profil ?? '—' }}</td>
                            <td>{{ $f->contact ?? '—' }}</td>
                            <td>{{ $f->email ?? '—' }}</td>
                            <td>
                                <div class="row-actions" onclick="event.stopPropagation()">
                                    <button type="button" class="row-btn edit btn-edit-row" title="Modifier">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="{{ route('fournisseurs.fiche.print', $f) }}" target="_blank" class="row-btn print" title="Imprimer">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <a href="{{ route('fournisseurs.fiche.export-pdf') }}?id={{ $f->id }}" target="_blank" class="row-btn pdf" title="Exporter PDF">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                    <form method="POST" action="{{ route('fournisseurs.fiche.destroy', $f) }}"
                                          onsubmit="return confirm('Supprimer ce fournisseur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="row-btn delete" title="Supprimer">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-slate-400" style="font-size:0.95rem">
                                <i class="fa-solid fa-inbox text-2xl block mb-2 opacity-40"></i>
                                Aucun fournisseur enregistré
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('ficheForm');
    const formMethod = document.getElementById('formMethod');
    const fournisseurId = document.getElementById('fournisseurId');
    const editBadge = document.getElementById('editBadge');
    const btnCancel = document.getElementById('btnCancel');
    const storeUrl = @json(route('fournisseurs.fiche.store'));

    function loadRow(row) {
        document.getElementById('raison_sociale').value = row.dataset.raison || '';
        document.getElementById('nom_responsable').value = row.dataset.responsable || '';
        document.getElementById('profil').value = row.dataset.profil || '';
        document.getElementById('contact').value = row.dataset.contact || '';
        document.getElementById('email').value = row.dataset.email || '';

        form.action = row.dataset.updateUrl;
        formMethod.value = 'PUT';
        fournisseurId.value = row.dataset.id;
        editBadge.classList.add('is-visible');
        btnCancel.style.display = 'inline-flex';

        document.querySelectorAll('.fournisseur-row').forEach((r) => r.classList.remove('is-editing'));
        row.classList.add('is-editing');
        document.getElementById('raison_sociale').focus();
    }

    function resetForm() {
        form.reset();
        form.action = storeUrl;
        formMethod.value = 'POST';
        fournisseurId.value = '';
        editBadge.classList.remove('is-visible');
        btnCancel.style.display = 'none';
        document.querySelectorAll('.fournisseur-row').forEach((r) => r.classList.remove('is-editing'));
    }

    document.querySelectorAll('.fournisseur-row').forEach((row) => {
        row.addEventListener('dblclick', () => loadRow(row));
        row.querySelector('.btn-edit-row')?.addEventListener('click', () => loadRow(row));
    });

    btnCancel.addEventListener('click', resetForm);
</script>
@endsection
