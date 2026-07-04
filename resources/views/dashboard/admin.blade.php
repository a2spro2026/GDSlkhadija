@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue analytique — ' . $chartYear)

@push('styles')
<style>
    .app-main:has(.dash-page) {
        height: 100vh;
        overflow: hidden;
    }
    .app-main:has(.dash-page) .page-body {
        flex: 1;
        min-height: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding-top: 1rem;
        padding-bottom: 0.5rem;
    }
    .app-main:has(.dash-page) .page-container {
        flex: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        max-width: 100%;
    }
    .app-main:has(.dash-page) .page-container > .mb-5 {
        flex-shrink: 0;
        margin-bottom: 0.5rem !important;
    }

    .dash-page {
        flex: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        gap: 0;
        overflow: hidden;
    }

    .dash-kpi-bar {
        flex-shrink: 0;
        position: sticky;
        top: 0;
        z-index: 20;
        padding-bottom: 0.75rem;
        background: linear-gradient(180deg, #eef2f7 75%, rgba(238,242,247,0));
    }

    .dash-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }
    @media (max-width: 1024px) {
        .dash-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 560px) {
        .dash-kpi-grid { grid-template-columns: 1fr; }
    }

    .dash-kpi {
        position: relative;
        border-radius: 1rem;
        padding: 1rem 1.1rem;
        color: #fff;
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .dash-kpi:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.18);
    }
    .dash-kpi::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -20%;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        pointer-events: none;
    }
    .dash-kpi::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        pointer-events: none;
    }
    .dash-kpi--achats { background: linear-gradient(135deg, #1e40af, #2563EB); }
    .dash-kpi--commande { background: linear-gradient(135deg, #047857, #10b981); }
    .dash-kpi--charges { background: linear-gradient(135deg, #ca8a04, #eab308); }
    .dash-kpi--solde { background: linear-gradient(135deg, #b91c1c, #ef4444); }

    .dash-kpi-icon {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.35rem;
    }
    .dash-kpi-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.92;
    }
    .dash-kpi-value {
        margin-top: 0.3rem;
        font-family: 'Poppins', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        position: relative;
        z-index: 1;
    }

    .dash-scroll {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding-bottom: 0.5rem;
    }

    .dash-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    @media (max-width: 900px) {
        .dash-grid-2 { grid-template-columns: 1fr; }
    }

    .dash-panel {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .dash-panel-head {
        padding: 0.7rem 1rem;
        background: #fafbfc;
        border-bottom: 1px solid #e2e8f0;
    }
    .dash-panel-head h3 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.8rem;
        font-weight: 600;
        color: #071A35;
    }
    .dash-table-wrap { overflow: auto; }
    .dash-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.76rem;
        min-width: 420px;
    }
    .dash-table thead th {
        padding: 0.65rem 0.75rem;
        text-align: left;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #e2e8f0;
        background: #071A35;
        border-bottom: 2px solid #f59e0b;
        white-space: nowrap;
    }
    .dash-table tbody td {
        padding: 0.6rem 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .dash-table tbody tr:hover { background: #fffbeb; }

    .dash-statut {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 999px;
        font-size: 0.65rem;
        font-weight: 700;
    }
    .dash-statut--livre { background: #dcfce7; color: #166534; }
    .dash-statut--en_attente { background: #fef9c3; color: #854d0e; }
    .dash-statut--annule { background: #fee2e2; color: #991b1b; }
    .dash-solde-due { color: #dc2626; font-weight: 700; }
    .dash-solde-zero { color: #64748b; }
    .dash-paye-ok { color: #059669; font-weight: 700; }

    .dash-chart-panel { min-height: 320px; }
    .dash-chart-body { padding: 1rem; height: 280px; }
</style>
@endpush

@section('content')
<div class="dash-page">
    <div class="dash-kpi-bar">
        <div class="dash-kpi-grid">
            <div class="dash-kpi dash-kpi--achats">
                <div class="dash-kpi-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                <div class="dash-kpi-label">Total Achats</div>
                <div class="dash-kpi-value">{{ number_format($totalAchats, 2, ',', ' ') }} DH</div>
            </div>
            <div class="dash-kpi dash-kpi--commande">
                <div class="dash-kpi-icon"><i class="fa-solid fa-file-lines"></i></div>
                <div class="dash-kpi-label">Total Bon de Commande</div>
                <div class="dash-kpi-value">{{ number_format($totalBonCommande, 2, ',', ' ') }} DH</div>
            </div>
            <div class="dash-kpi dash-kpi--charges">
                <div class="dash-kpi-icon"><i class="fa-solid fa-receipt"></i></div>
                <div class="dash-kpi-label">Total Charges</div>
                <div class="dash-kpi-value">{{ number_format($totalCharges, 2, ',', ' ') }} DH</div>
            </div>
            <div class="dash-kpi dash-kpi--solde">
                <div class="dash-kpi-icon"><i class="fa-solid fa-scale-unbalanced"></i></div>
                <div class="dash-kpi-label">Total Solde</div>
                <div class="dash-kpi-value">{{ number_format($totalSolde, 2, ',', ' ') }} DH</div>
            </div>
        </div>
    </div>

    <div class="dash-scroll">
        <div class="dash-grid-2">
            <div class="dash-panel">
                <div class="dash-panel-head">
                    <h3><i class="fa-solid fa-clock-rotate-left text-amber-500 mr-1"></i> Les 5 Derniers Bon de Commande</h3>
                </div>
                <div class="dash-table-wrap">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Ville</th>
                                <th>Adresse</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($derniersBonsCommande as $bc)
                                <tr>
                                    <td>{{ $bc->date_bon->format('d/m/Y') }}</td>
                                    <td class="font-semibold">{{ $bc->client }}</td>
                                    <td>{{ $bc->ville ?? '—' }}</td>
                                    <td>{{ $bc->adresse ?? '—' }}</td>
                                    <td>
                                        <span class="dash-statut dash-statut--{{ $bc->statut }}">
                                            {{ $statutLabels[$bc->statut] ?? $bc->statut }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-6 text-slate-400">Aucun bon de commande</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dash-panel">
                <div class="dash-panel-head">
                    <h3><i class="fa-solid fa-file-invoice text-amber-500 mr-1"></i> Les 5 Derniers Bon D'achats</h3>
                </div>
                <div class="dash-table-wrap">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Fournisseur</th>
                                <th>Mnt Bn</th>
                                <th>Mnt Payé</th>
                                <th>Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($derniersBonsAchats as $ba)
                                <tr>
                                    <td>{{ $ba['date']->format('d/m/Y') }}</td>
                                    <td class="font-semibold">{{ $ba['fournisseur'] }}</td>
                                    <td>{{ number_format($ba['montant'], 2, ',', ' ') }}</td>
                                    <td class="{{ $ba['solde'] <= 0 ? 'dash-paye-ok' : '' }}">{{ number_format($ba['montant_paye'], 2, ',', ' ') }}</td>
                                    <td class="{{ $ba['solde'] > 0 ? 'dash-solde-due' : 'dash-solde-zero' }}">{{ number_format($ba['solde'], 2, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-6 text-slate-400">Aucun bon d'achat</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dash-panel dash-chart-panel">
            <div class="dash-panel-head">
                <h3><i class="fa-solid fa-chart-column text-amber-500 mr-1"></i> Évolution {{ $chartYear }} — Bon Achats / Bon de Cmd / Charges</h3>
            </div>
            <div class="dash-chart-body">
                <canvas id="dashChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const chartData = @json($chartData);
    const ctx = document.getElementById('dashChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Bon Achats',
                        data: chartData.achats,
                        backgroundColor: 'rgba(37, 99, 235, 0.85)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Bon de Cmd',
                        data: chartData.commandes,
                        backgroundColor: 'rgba(16, 185, 129, 0.85)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Charges',
                        data: chartData.charges,
                        backgroundColor: 'rgba(234, 179, 8, 0.9)',
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { size: 11, family: 'Inter' }, boxWidth: 12 },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 10 },
                            callback: (v) => Number(v).toLocaleString('fr-FR') + ' DH',
                        },
                    },
                },
            },
        });
    }
</script>
@endpush
