<?php

namespace App\Http\Controllers;

use App\Models\BonAchat;
use App\Models\BonCommande;
use App\Models\Charge;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->canManage()) {
            return $this->adminDashboard();
        }

        return $this->technicianDashboard();
    }

    private function adminDashboard()
    {
        $bonsAchats = BonAchat::with([
            'fournisseur',
            'reglements' => fn ($q) => $q->where('statut', 'paye'),
        ])->get();

        $totalAchats = (float) $bonsAchats->sum('total');
        $totalSolde = $bonsAchats->sum(fn (BonAchat $bon) => $bon->solde());
        $totalBonCommande = (float) BonCommande::sum('montant');
        $totalCharges = (float) Charge::sum('montant');

        $derniersBonsCommande = BonCommande::orderByDesc('date_bon')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $derniersBonsAchats = BonAchat::with([
            'fournisseur',
            'reglements' => fn ($q) => $q->where('statut', 'paye'),
        ])
            ->orderByDesc('date_bon')
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(fn (BonAchat $bon) => [
                'date' => $bon->date_bon,
                'fournisseur' => $bon->fournisseur->raison_sociale ?? '—',
                'montant' => (float) $bon->total,
                'montant_paye' => $bon->montantPaye(),
                'solde' => $bon->solde(),
            ]);

        $year = (int) date('Y');
        $chartData = $this->monthlyChartData($year);

        return view('dashboard.admin', [
            'totalAchats' => $totalAchats,
            'totalBonCommande' => $totalBonCommande,
            'totalCharges' => $totalCharges,
            'totalSolde' => $totalSolde,
            'derniersBonsCommande' => $derniersBonsCommande,
            'derniersBonsAchats' => $derniersBonsAchats,
            'statutLabels' => BonCommande::statutLabels(),
            'chartYear' => $year,
            'chartData' => $chartData,
        ]);
    }

    private function monthlyChartData(int $year): array
    {
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

        $achats = BonAchat::query()
            ->whereYear('date_bon', $year)
            ->selectRaw('MONTH(date_bon) as mois, SUM(total) as total')
            ->groupBy('mois')
            ->pluck('total', 'mois');

        $commandes = BonCommande::query()
            ->whereYear('date_bon', $year)
            ->selectRaw('MONTH(date_bon) as mois, SUM(montant) as total')
            ->groupBy('mois')
            ->pluck('total', 'mois');

        $charges = Charge::query()
            ->whereYear('date_charge', $year)
            ->selectRaw('MONTH(date_charge) as mois, SUM(montant) as total')
            ->groupBy('mois')
            ->pluck('total', 'mois');

        $achatsData = [];
        $commandesData = [];
        $chargesData = [];

        for ($m = 1; $m <= 12; $m++) {
            $achatsData[] = round((float) ($achats[$m] ?? 0), 2);
            $commandesData[] = round((float) ($commandes[$m] ?? 0), 2);
            $chargesData[] = round((float) ($charges[$m] ?? 0), 2);
        }

        return [
            'labels' => $months,
            'achats' => $achatsData,
            'commandes' => $commandesData,
            'charges' => $chargesData,
        ];
    }

    private function technicianDashboard()
    {
        $user = auth()->user();

        $myTasks = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['en_attente', 'en_cours'])
            ->orderByRaw("FIELD(priority, 'urgente', 'haute', 'normale', 'basse')")
            ->orderBy('due_date')
            ->get();

        $completedThisMonth = Task::where('assigned_to', $user->id)
            ->where('status', 'terminee')
            ->whereMonth('completed_at', now()->month)
            ->count();

        $stats = [
            'pending' => $myTasks->where('status', 'en_attente')->count(),
            'in_progress' => $myTasks->where('status', 'en_cours')->count(),
            'completed_month' => $completedThisMonth,
        ];

        return view('dashboard.technician', compact('myTasks', 'stats'));
    }
}
