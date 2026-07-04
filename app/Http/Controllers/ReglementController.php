<?php

namespace App\Http\Controllers;

use App\Models\BonAchat;
use App\Models\Fournisseur;
use App\Models\Reglement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReglementController extends Controller
{
    public function index()
    {
        $reglements = Reglement::with(['fournisseur', 'bonsAchats'])
            ->orderByDesc('date_reglement')
            ->orderByDesc('id')
            ->get();

        return view('fournisseurs.reglement', [
            'reglements' => $reglements,
            'typeLabels' => Reglement::typeLabels(),
            'statutLabels' => Reglement::statutLabels(),
        ]);
    }

    public function create()
    {
        return response()
            ->view('fournisseurs.reglement-form', $this->formViewData())
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function edit(Reglement $reglement)
    {
        $this->ensureCanModifyVerrouille($reglement);

        $reglement->load('bonsAchats');

        return view('fournisseurs.reglement-form', array_merge(
            $this->formViewData($reglement),
            ['reglement' => $reglement, 'isEdit' => true]
        ));
    }

    private function formViewData(?Reglement $reglement = null): array
    {
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();

        $bonsEnAttente = BonAchat::with([
            'fournisseur',
            'reglements' => fn ($q) => $q->where('statut', 'paye'),
        ])
            ->orderByDesc('date_bon')
            ->get()
            ->filter(function (BonAchat $bon) use ($reglement) {
                if ($bon->solde() > 0) {
                    return true;
                }

                return $reglement && $reglement->bonsAchats->contains('id', $bon->id);
            })
            ->values();

        return [
            'fournisseurs' => $fournisseurs,
            'bonsEnAttente' => $bonsEnAttente,
            'nextReference' => $this->generateNextReference(),
            'typeLabels' => Reglement::typeLabels(),
            'statutLabels' => Reglement::statutLabels(),
            'isEdit' => false,
        ];
    }

    public function soldeFournisseur(Fournisseur $fournisseur)
    {
        $bons = BonAchat::where('fournisseur_id', $fournisseur->id)
            ->orderByDesc('date_bon')
            ->get()
            ->map(fn (BonAchat $bon) => [
                'id' => $bon->id,
                'numero_bon' => $bon->numero_bon,
                'date_bon' => $bon->date_bon->format('d/m/Y'),
                'montant_cmd' => (float) $bon->total,
                'montant_paye' => $bon->montantPaye(),
                'solde' => $bon->solde(),
            ])
            ->filter(fn ($b) => $b['solde'] > 0)
            ->values();

        return response()->json([
            'solde_total' => $fournisseur->soldeTotal(),
            'bons' => $bons,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateReglement($request);

        DB::transaction(function () use ($validated) {
            $reglement = Reglement::create([
                'reference' => $validated['reference'],
                'date_reglement' => $validated['date_reglement'],
                'fournisseur_id' => $validated['fournisseur_id'],
                'type_reglement' => $validated['type_reglement'],
                'numero' => $validated['type_reglement'] === 'esp' ? null : ($validated['numero'] ?? null),
                'banque' => $validated['type_reglement'] === 'esp' ? null : ($validated['banque'] ?? null),
                'montant' => $validated['montant'],
                'nom_tire' => $validated['nom_tire'] ?? null,
                'date_decaissement' => $validated['date_decaissement'] ?? null,
                'statut' => $validated['statut'] ?? 'paye',
                'user_id' => auth()->id(),
            ]);

            $this->syncBonsAffectation($reglement, $validated);
        });

        return redirect()->route('fournisseurs.reglement.index');
    }

    public function update(Request $request, Reglement $reglement)
    {
        $this->ensureCanModifyVerrouille($reglement);

        $validated = $this->validateReglement($request, $reglement);

        DB::transaction(function () use ($reglement, $validated) {
            $reglement->update([
                'reference' => $validated['reference'],
                'date_reglement' => $validated['date_reglement'],
                'fournisseur_id' => $validated['fournisseur_id'],
                'type_reglement' => $validated['type_reglement'],
                'numero' => $validated['type_reglement'] === 'esp' ? null : ($validated['numero'] ?? null),
                'banque' => $validated['type_reglement'] === 'esp' ? null : ($validated['banque'] ?? null),
                'montant' => $validated['montant'],
                'nom_tire' => $validated['nom_tire'] ?? null,
                'date_decaissement' => $validated['date_decaissement'] ?? null,
                'statut' => $validated['statut'],
            ]);

            $reglement->bonsAchats()->detach();
            $this->syncBonsAffectation($reglement, $validated);
        });

        return redirect()->route('fournisseurs.reglement.index');
    }

    public function destroy(Reglement $reglement)
    {
        $this->ensureCanModifyVerrouille($reglement);

        $reglement->delete();

        return redirect()->route('fournisseurs.reglement.index');
    }

    public function show(Reglement $reglement)
    {
        $reglement->load(['fournisseur', 'bonsAchats.lignes']);

        return view('fournisseurs.reglement-show', [
            'reglement' => $reglement,
            'typeLabels' => Reglement::typeLabels(),
            'statutLabels' => Reglement::statutLabels(),
        ]);
    }

    public function print(Reglement $reglement)
    {
        $reglement->load(['fournisseur', 'bonsAchats']);

        return view('fournisseurs.reglement-print', [
            'reglement' => $reglement,
            'typeLabels' => Reglement::typeLabels(),
            'statutLabels' => Reglement::statutLabels(),
        ]);
    }

    private function validateReglement(Request $request, ?Reglement $reglement = null): array
    {
        $validated = $request->validate([
            'reference' => [
                'required', 'string', 'max:50',
                Rule::unique('reglements', 'reference')->ignore($reglement?->id),
            ],
            'date_reglement' => 'required|date',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'type_reglement' => 'required|in:esp,chq,eff,vir,vers',
            'numero' => 'nullable|string|max:100',
            'banque' => 'nullable|string|max:100',
            'montant' => 'required|numeric|min:0.01',
            'nom_tire' => 'nullable|string|max:255',
            'date_decaissement' => 'nullable|date',
            'statut' => 'required|in:paye,impaye,reporte,devalide',
            'bons' => 'nullable|array',
            'bons.*.bon_achat_id' => 'required|exists:bons_achats,id',
            'bons.*.montant_affecte' => 'required|numeric|min:0.01',
        ], [
            'fournisseur_id.required' => 'Sélectionnez un fournisseur.',
            'reference.unique' => 'Cette référence existe déjà.',
        ]);

        if ($validated['type_reglement'] !== 'esp') {
            $request->validate([
                'numero' => 'required|string|max:100',
                'banque' => 'required|string|max:100',
            ]);
            $validated['numero'] = $request->input('numero');
            $validated['banque'] = $request->input('banque');
        }

        return $validated;
    }

    private function ensureCanModifyVerrouille(Reglement $reglement): void
    {
        if ($reglement->isVerrouille() && ! auth()->user()?->isAdmin()) {
            abort(403, 'Seul l\'administrateur peut modifier ou supprimer un règlement payé.');
        }
    }

    /**
     * Lie le règlement aux bons cochés, ou affecte automatiquement
     * aux bons en attente du fournisseur si aucun bon n'est sélectionné.
     */
    private function syncBonsAffectation(Reglement $reglement, array $validated): void
    {
        $bons = $validated['bons'] ?? [];

        if (! empty($bons)) {
            foreach ($bons as $bonData) {
                $reglement->bonsAchats()->attach($bonData['bon_achat_id'], [
                    'montant_affecte' => $bonData['montant_affecte'],
                ]);
            }

            return;
        }

        $remaining = (float) $validated['montant'];
        if ($remaining <= 0) {
            return;
        }

        $bonsEnAttente = BonAchat::where('fournisseur_id', $validated['fournisseur_id'])
            ->orderBy('date_bon')
            ->orderBy('id')
            ->get();

        foreach ($bonsEnAttente as $bon) {
            if ($remaining <= 0) {
                break;
            }

            $solde = $bon->solde();
            if ($solde <= 0) {
                continue;
            }

            $affecte = min($remaining, $solde);
            $reglement->bonsAchats()->attach($bon->id, [
                'montant_affecte' => round($affecte, 2),
            ]);
            $remaining = round($remaining - $affecte, 2);
        }

        if ($remaining > 0.005 && $bonsEnAttente->isNotEmpty()) {
            $lastBon = $bonsEnAttente->last();
            $pivot = $reglement->bonsAchats()->where('bon_achat_id', $lastBon->id)->first();

            if ($pivot) {
                $reglement->bonsAchats()->updateExistingPivot($lastBon->id, [
                    'montant_affecte' => round((float) $pivot->pivot->montant_affecte + $remaining, 2),
                ]);
            } else {
                $reglement->bonsAchats()->attach($lastBon->id, [
                    'montant_affecte' => round($remaining, 2),
                ]);
            }
        }
    }

    private function generateNextReference(): string
    {
        $year = date('Y');
        $prefix = 'REG-'.$year.'-';

        $last = Reglement::where('reference', 'like', $prefix.'%')
            ->orderByDesc('reference')
            ->value('reference');

        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
