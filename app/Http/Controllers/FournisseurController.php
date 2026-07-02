<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();

        return view('fournisseurs.fiche', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateFournisseur($request);
        Fournisseur::create($validated);

        return redirect()->route('fournisseurs.fiche.index');
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $this->validateFournisseur($request);
        $fournisseur->update($validated);

        return redirect()->route('fournisseurs.fiche.index');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()->route('fournisseurs.fiche.index');
    }

    public function print(Fournisseur $fournisseur)
    {
        return view('fournisseurs.print', [
            'fournisseurs' => collect([$fournisseur]),
            'title' => 'Fiche fournisseur — '.$fournisseur->raison_sociale,
        ]);
    }

    public function printAll()
    {
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();

        return view('fournisseurs.print', [
            'fournisseurs' => $fournisseurs,
            'title' => 'Liste des fournisseurs',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Fournisseur::orderBy('raison_sociale');

        if ($request->filled('id')) {
            $query->where('id', $request->integer('id'));
        }

        $fournisseurs = $query->get();

        return view('fournisseurs.export-pdf', compact('fournisseurs'));
    }

    private function validateFournisseur(Request $request): array
    {
        return $request->validate([
            'raison_sociale' => 'required|string|max:255',
            'nom_responsable' => 'required|string|max:255',
            'profil' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ], [
            'raison_sociale.required' => 'La raison sociale est obligatoire.',
            'nom_responsable.required' => 'Le nom du responsable est obligatoire.',
            'email.email' => 'Adresse e-mail invalide.',
        ]);
    }
}
