@extends('layouts.app')

@section('title', 'Dépôt IAM')
@section('page-title', 'Dépôt IAM')
@section('page-subtitle', 'Stock articles fournisseur IAM')

@push('styles')
<style>
    .iam-page { display: flex; flex-direction: column; gap: 0.75rem; max-width: 100%; }
    .iam-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(15,23,42,0.06); overflow: hidden; }
    .iam-head { padding: 0.7rem 1rem; background: linear-gradient(90deg, #071A35, #0F4C81); color: #fff; }
    .iam-head h2 { margin: 0; font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 600; }
    .iam-head p { margin: 0.25rem 0 0; font-size: 0.68rem; opacity: 0.85; }
    .iam-table-wrap { overflow: auto; }
    .iam-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; min-width: 900px; }
    .iam-table thead th { padding: 0.7rem 0.8rem; text-align: left; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; color: #e2e8f0; background: #071A35; border-bottom: 2px solid #f59e0b; }
    .iam-table tbody td { padding: 0.65rem 0.8rem; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .iam-table tbody tr:hover { background: #ecfeff; }
    .stock-final { font-weight: 700; color: #0891b2; }
    .row-actions { display: flex; gap: 0.3rem; justify-content: flex-end; }
    .row-btn { width: 1.9rem; height: 1.9rem; border-radius: 0.4rem; border: 1px solid #e2e8f0; background: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; cursor: pointer; text-decoration: none; color: #475569; }
    .row-btn.view { color: #0891b2; border-color: #a5f3fc; background: #ecfeff; }
    .row-btn.edit { color: #2563EB; border-color: #bfdbfe; background: #eff6ff; }
    .row-btn.print { color: #7c3aed; border-color: #ddd6fe; background: #f5f3ff; }
    .row-btn.pdf { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
    .iam-badge { display: inline-block; padding: 0.25rem 0.55rem; border-radius: 999px; font-size: 0.68rem; font-weight: 700; white-space: nowrap; }
    .iam-statut-actif { background: #dcfce7; color: #166534; }
    .iam-statut-inactif { background: #f1f5f9; color: #64748b; }
    .iam-etat-dispo { background: #dcfce7; color: #166534; }
    .iam-etat-faible { background: #fef9c3; color: #854d0e; }
    .iam-etat-rupture { background: #fee2e2; color: #991b1b; }
    .iam-table tbody tr.is-inactif { opacity: 0.72; }
</style>
@endpush

@section('content')
<div class="iam-page">
    @if(session('success'))
        <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">{{ session('success') }}</div>
    @endif

    <div class="iam-card">
        <div class="iam-head">
            <h2><i class="fa-solid fa-warehouse mr-2"></i>Dépôt IAM</h2>
            <p>Alimenté automatiquement par les bons d'achat du fournisseur IAM</p>
        </div>
        <div class="iam-table-wrap">
            <table class="iam-table">
                <thead>
                    <tr>
                        <th>Réf</th>
                        <th>Désignation</th>
                        <th>Stock Initial</th>
                        <th>Entrée</th>
                        <th>Sortie</th>
                        <th>Stock Final</th>
                        <th>Statut</th>
                        <th>État</th>
                        <th style="text-align:right;width:150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $a)
                        <tr class="{{ $a->statut === 'inactif' ? 'is-inactif' : '' }}">
                            <td class="font-semibold">{{ $a->reference ?: '—' }}</td>
                            <td>{{ $a->designation }}</td>
                            <td>{{ number_format($a->stock_initial, 2, ',', ' ') }}</td>
                            <td class="text-green-700 font-semibold">{{ number_format($a->entree, 2, ',', ' ') }}</td>
                            <td class="text-red-600">{{ number_format($a->sortie, 2, ',', ' ') }}</td>
                            <td class="stock-final">{{ number_format($a->stockFinal(), 2, ',', ' ') }}</td>
                            <td><span class="iam-badge iam-statut-{{ $a->statut }}">{{ $a->statutLabel() }}</span></td>
                            <td><span class="iam-badge iam-etat-{{ $a->etat }}">{{ $a->etatLabel() }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('depot.iam.show', $a) }}" target="_blank" class="row-btn view" title="Voir"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('depot.iam.edit', $a) }}" class="row-btn edit" title="Corriger"><i class="fa-solid fa-pen"></i></a>
                                    <a href="{{ route('depot.iam.print', $a) }}" target="_blank" class="row-btn print" title="Imprimer"><i class="fa-solid fa-print"></i></a>
                                    <a href="{{ route('depot.iam.export-pdf', $a) }}" target="_blank" class="row-btn pdf" title="PDF"><i class="fa-solid fa-file-pdf"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-10 text-slate-400">Aucun article — saisissez un bon d'achat fournisseur IAM</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
