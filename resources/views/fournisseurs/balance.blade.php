@extends('layouts.app')

@section('title', 'Balance')
@section('page-title', 'Balance')
@section('page-subtitle', 'Balance et soldes fournisseurs')

@push('styles')
<style>
    .bal-page { display: flex; flex-direction: column; gap: 0.85rem; max-width: 100%; }

    .bal-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }
    @media (max-width: 768px) {
        .bal-cards { grid-template-columns: 1fr; }
    }

    .bal-card {
        border-radius: 1rem;
        padding: 1rem 1.1rem;
        color: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .bal-card--achats { background: linear-gradient(135deg, #1d4ed8, #2563EB); }
    .bal-card--paye { background: linear-gradient(135deg, #047857, #059669); }
    .bal-card--solde { background: linear-gradient(135deg, #b91c1c, #dc2626); }

    .bal-card-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        opacity: 0.92;
    }
    .bal-card-value {
        font-family: 'Poppins', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .bal-card-icon {
        font-size: 0.85rem;
        opacity: 0.85;
        margin-bottom: 0.15rem;
    }

    .bal-table-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .bal-table-head {
        padding: 0.7rem 1rem;
        background: #fafbfc;
        border-bottom: 1px solid #e2e8f0;
    }
    .bal-table-head h2 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        color: #071A35;
    }
    .bal-table-wrap { overflow: auto; }
    .bal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.78rem;
        min-width: 800px;
    }
    .bal-table thead th {
        padding: 0.7rem 0.8rem;
        text-align: left;
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #e2e8f0;
        background: #071A35;
        border-bottom: 2px solid #f59e0b;
    }
    .bal-table tbody td {
        padding: 0.65rem 0.8rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .bal-table tbody tr:hover { background: #fffbeb; }
    .bal-paye-ok { color: #059669; font-weight: 700; }
    .bal-impaye { color: #dc2626; font-weight: 700; }
    .bal-solde-due { color: #dc2626; font-weight: 700; }
    .bal-solde-zero { color: #64748b; }
    .bal-statut { display: inline-block; padding: 0.25rem 0.55rem; border-radius: 999px; font-size: 0.68rem; font-weight: 700; }
    .bal-statut--paye { background: #dcfce7; color: #166534; }
    .bal-statut--impaye { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="bal-page">
    <div class="bal-cards">
        <div class="bal-card bal-card--achats">
            <div class="bal-card-icon"><i class="fa-solid fa-file-invoice"></i></div>
            <div class="bal-card-label">Total Bon Achats</div>
            <div class="bal-card-value">{{ number_format($totalAchats, 2, ',', ' ') }} DH</div>
        </div>
        <div class="bal-card bal-card--paye">
            <div class="bal-card-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="bal-card-label">Total Payé</div>
            <div class="bal-card-value">{{ number_format($totalPaye, 2, ',', ' ') }} DH</div>
        </div>
        <div class="bal-card bal-card--solde">
            <div class="bal-card-icon"><i class="fa-solid fa-scale-unbalanced"></i></div>
            <div class="bal-card-label">Total Solde</div>
            <div class="bal-card-value">{{ number_format($totalSolde, 2, ',', ' ') }} DH</div>
        </div>
    </div>

    <div class="bal-table-card">
        <div class="bal-table-head">
            <h2><i class="fa-solid fa-table text-amber-500 mr-1"></i> Détail par bon d'achat</h2>
        </div>
        <div class="bal-table-wrap">
            <table class="bal-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N° Bon</th>
                        <th>Fournisseur</th>
                        <th>Montant Bon</th>
                        <th>Montant Payé</th>
                        <th>Solde</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lignes as $ligne)
                        <tr>
                            <td>{{ $ligne['date']->format('d/m/Y') }}</td>
                            <td class="font-semibold">{{ $ligne['numero_bon'] }}</td>
                            <td>{{ $ligne['fournisseur'] }}</td>
                            <td>{{ number_format($ligne['montant_bon'], 2, ',', ' ') }}</td>
                            <td class="{{ $ligne['statut'] === 'paye' ? 'bal-paye-ok' : ($ligne['montant_impaye'] > 0 ? 'bal-impaye' : '') }}">
                                {{ number_format($ligne['montant_paye'], 2, ',', ' ') }}
                            </td>
                            <td class="{{ $ligne['solde'] > 0 ? 'bal-solde-due' : 'bal-solde-zero' }}">
                                {{ number_format($ligne['solde'], 2, ',', ' ') }}
                            </td>
                            <td>
                                <span class="bal-statut bal-statut--{{ $ligne['statut'] }}">
                                    {{ $statutLabels[$ligne['statut']] ?? $ligne['statut'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">Aucun bon d'achat enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
