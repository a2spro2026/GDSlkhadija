<?php

namespace App\Http\Controllers;

use App\Models\BonAchat;
use Illuminate\View\View;

class BalanceController extends Controller
{
    public function index(): View
    {
        $bons = BonAchat::with(['fournisseur', 'reglements'])
            ->orderByDesc('date_bon')
            ->orderByDesc('id')
            ->get();

        $lignes = $bons->map(function (BonAchat $bon) {
            $montantPaye = $bon->montantPaye();
            $solde = $bon->solde();
            $statut = $bon->statutBalance();

            return [
                'date' => $bon->date_bon,
                'numero_bon' => $bon->numero_bon,
                'fournisseur' => $bon->fournisseur->raison_sociale ?? '—',
                'montant_bon' => (float) $bon->total,
                'montant_paye' => $montantPaye,
                'montant_impaye' => $bon->montantImpaye(),
                'solde' => $solde,
                'statut' => $statut,
            ];
        });

        return view('fournisseurs.balance', [
            'lignes' => $lignes,
            'totalAchats' => $lignes->sum('montant_bon'),
            'totalPaye' => $lignes->sum('montant_paye'),
            'totalSolde' => $lignes->sum('solde'),
            'statutLabels' => [
                'paye' => 'Payé',
                'impaye' => 'Impayé',
            ],
        ]);
    }
}
