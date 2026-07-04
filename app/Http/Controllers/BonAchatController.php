<?php

namespace App\Http\Controllers;

use App\Models\BonAchat;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BonAchatController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();
        $bonsAchats = BonAchat::with(['fournisseur', 'lignes'])
            ->orderByDesc('date_bon')
            ->orderByDesc('id')
            ->get();

        return view('fournisseurs.bons-achats', [
            'fournisseurs' => $fournisseurs,
            'bonsAchats' => $bonsAchats,
            'nextNumero' => $this->generateNextNumero(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateBon($request);

        DB::transaction(function () use ($validated) {
            $this->persistBon(BonAchat::create([
                'date_bon' => $validated['date_bon'],
                'numero_bon' => $validated['numero_bon'],
                'fournisseur_id' => $validated['fournisseur_id'],
                'total' => 0,
                'user_id' => auth()->id(),
            ]), $validated['lignes']);
        });

        return redirect()->route('fournisseurs.bons-achats.index');
    }

    public function update(Request $request, BonAchat $bonAchat)
    {
        $validated = $this->validateBon($request, $bonAchat);

        DB::transaction(function () use ($bonAchat, $validated) {
            $bonAchat->update([
                'date_bon' => $validated['date_bon'],
                'numero_bon' => $validated['numero_bon'],
                'fournisseur_id' => $validated['fournisseur_id'],
            ]);
            $bonAchat->lignes()->delete();
            $this->persistBon($bonAchat, $validated['lignes']);
        });

        return redirect()->route('fournisseurs.bons-achats.index');
    }

    public function destroy(BonAchat $bonAchat)
    {
        $bonAchat->delete();

        return redirect()->route('fournisseurs.bons-achats.index');
    }

    public function print(BonAchat $bonAchat)
    {
        $bonAchat->load(['fournisseur', 'lignes']);

        return view('fournisseurs.bons-achats-print', compact('bonAchat'));
    }

    public function exportPdf(BonAchat $bonAchat)
    {
        $bonAchat->load(['fournisseur', 'lignes']);

        return view('fournisseurs.bons-achats-export-pdf', compact('bonAchat'));
    }

    private function validateBon(Request $request, ?BonAchat $bonAchat = null): array
    {
        return $request->validate([
            'date_bon' => 'required|date',
            'numero_bon' => [
                'required', 'string', 'max:50',
                Rule::unique('bons_achats', 'numero_bon')->ignore($bonAchat?->id),
            ],
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'lignes' => 'required|array|min:1',
            'lignes.*.reference' => 'nullable|string|max:100',
            'lignes.*.designation' => 'required|string|max:255',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
            'lignes.*.sous_total' => 'required|numeric|min:0',
        ], [
            'lignes.required' => 'Ajoutez au moins une ligne au bon d\'achat.',
            'lignes.min' => 'Ajoutez au moins une ligne au bon d\'achat.',
            'fournisseur_id.required' => 'Sélectionnez un fournisseur.',
            'numero_bon.unique' => 'Ce numéro de bon existe déjà.',
        ]);
    }

    private function persistBon(BonAchat $bon, array $lignes): void
    {
        $total = collect($lignes)->sum('sous_total');
        $bon->update(['total' => $total]);

        foreach ($lignes as $ligne) {
            $bon->lignes()->create([
                'reference' => $ligne['reference'] ?? null,
                'designation' => $ligne['designation'],
                'quantite' => $ligne['quantite'],
                'prix_unitaire' => $ligne['prix_unitaire'],
                'sous_total' => $ligne['sous_total'],
            ]);
        }
    }

    private function generateNextNumero(): string
    {
        $year = date('Y');
        $prefix = 'BA-'.$year.'-';

        $last = BonAchat::where('numero_bon', 'like', $prefix.'%')
            ->orderByDesc('numero_bon')
            ->value('numero_bon');

        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
