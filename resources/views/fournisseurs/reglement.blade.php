@extends('layouts.app')

@section('title', 'Règlement')
@section('page-title', 'Règlement')
@section('page-subtitle', 'Liste des règlements fournisseurs')

@push('styles')
<style>
    .reg-page { display: flex; flex-direction: column; gap: 0.75rem; max-width: 100%; }
    .reg-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(15,23,42,0.06); overflow: hidden; }
    .reg-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
    .reg-toolbar h2 { margin: 0; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 600; color: #071A35; }
    .btn-nouveau { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; font-size: 0.78rem; font-weight: 600; border-radius: 0.45rem; border: 0; cursor: pointer; text-decoration: none; background: linear-gradient(135deg, #2563EB, #1d4ed8); color: #fff; }
    .btn-nouveau:hover { opacity: 0.92; color: #fff; }
    .reg-subhead { padding: 0.7rem 1rem; background: #fafbfc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
    .reg-subhead h3 { margin: 0; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; color: #071A35; }
    .reg-table-wrap { overflow: auto; }
    .reg-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; min-width: 900px; }
    .reg-table thead th { padding: 0.7rem 0.8rem; text-align: left; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; color: #e2e8f0; background: #071A35; border-bottom: 2px solid #f59e0b; }
    .reg-table tbody td { padding: 0.65rem 0.8rem; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .reg-table tbody tr:hover { background: #fffbeb; }
    .reg-table tbody tr.is-locked { background: #f1f5f9; }
    .reg-table tbody tr.is-locked:hover { background: #e8edf3; }
    .reg-table tbody tr.is-locked td { color: #94a3b8; }
    .reg-table tbody tr.is-locked .font-semibold { color: #94a3b8; }
    .reg-table tbody tr.is-locked .statut-badge { opacity: 0.75; }
    .statut-badge { display: inline-block; padding: 0.25rem 0.55rem; border-radius: 999px; font-size: 0.68rem; font-weight: 700; }
    .statut-paye { background: #dcfce7; color: #166534; }
    .statut-impaye { background: #fee2e2; color: #991b1b; }
    .statut-reporte { background: #fef9c3; color: #854d0e; }
    .statut-devalide { background: #ede9fe; color: #6d28d9; }
    .row-actions { display: flex; gap: 0.3rem; justify-content: flex-end; }
    .row-btn { width: 1.9rem; height: 1.9rem; border-radius: 0.4rem; border: 1px solid #e2e8f0; background: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; cursor: pointer; text-decoration: none; color: #475569; }
    .row-btn.view { color: #0891b2; border-color: #a5f3fc; background: #ecfeff; }
    .row-btn.edit { color: #2563EB; border-color: #bfdbfe; background: #eff6ff; }
    .row-btn.print { color: #7c3aed; border-color: #ddd6fe; background: #f5f3ff; }
    .row-btn.delete { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
    .row-actions form { display: inline-flex; margin: 0; }
</style>
@endpush

@section('content')
@php $isAdmin = auth()->user()?->isAdmin(); @endphp
<div class="reg-page">
    <div class="reg-toolbar">
        <h2><i class="fa-solid fa-money-check-dollar text-amber-500 mr-1"></i> Règlements enregistrés</h2>
        <a href="{{ route('fournisseurs.reglement.create') }}" class="btn-nouveau">
            <i class="fa-solid fa-plus"></i> Nouveau Règlement
        </a>
    </div>

    <div class="reg-card">
        <div class="reg-table-wrap">
            <table class="reg-table">
                <thead>
                    <tr>
                        <th>Réf</th>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Type</th>
                        <th>N°</th>
                        <th>Bnq</th>
                        <th>Date Décaiss</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th style="text-align:right;width:130px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reglements as $r)
                        @php $locked = $r->isVerrouille(); @endphp
                        <tr class="{{ $locked ? 'is-locked' : '' }}">
                            <td class="font-semibold">{{ $r->reference }}</td>
                            <td>{{ $r->date_reglement->format('d/m/Y') }}</td>
                            <td>{{ $r->fournisseur->raison_sociale ?? '—' }}</td>
                            <td>{{ $typeLabels[$r->type_reglement] ?? $r->type_reglement }}</td>
                            <td>{{ $r->numero ?? '—' }}</td>
                            <td>{{ $r->banque ?? '—' }}</td>
                            <td>{{ $r->date_decaissement?->format('d/m/Y') ?? '—' }}</td>
                            <td class="font-semibold">{{ number_format($r->montant, 2, ',', ' ') }}</td>
                            <td><span class="statut-badge statut-{{ $r->statut }}">{{ $statutLabels[$r->statut] ?? $r->statut }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('fournisseurs.reglement.show', $r) }}" target="_blank" class="row-btn view" title="Voir"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('fournisseurs.reglement.print', $r) }}" target="_blank" class="row-btn print" title="Imprimer"><i class="fa-solid fa-print"></i></a>
                                    @if(! $locked || $isAdmin)
                                        <a href="{{ route('fournisseurs.reglement.edit', $r) }}" class="row-btn edit" title="Modifier"><i class="fa-solid fa-pen"></i></a>
                                        <form method="POST" action="{{ route('fournisseurs.reglement.destroy', $r) }}" onsubmit="return confirm('Supprimer ce règlement ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="row-btn delete" title="Supprimer"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-8 text-slate-400">Aucun règlement — cliquez sur « Nouveau Règlement » pour en créer un</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
