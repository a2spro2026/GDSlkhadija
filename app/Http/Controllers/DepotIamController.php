<?php

namespace App\Http\Controllers;

use App\Models\DepotIamArticle;
use App\Services\DepotIamService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepotIamController extends Controller
{
    public function index(): View
    {
        DepotIamService::syncFromBonAchats();

        $articles = DepotIamArticle::orderBy('reference')
            ->orderBy('designation')
            ->get();

        return view('depot.iam', compact('articles'));
    }

    public function show(DepotIamArticle $article): View
    {
        return view('depot.iam-show', compact('article'));
    }

    public function edit(DepotIamArticle $article): View
    {
        return view('depot.iam-edit', compact('article'));
    }

    public function update(Request $request, DepotIamArticle $article)
    {
        $validated = $request->validate([
            'reference' => 'nullable|string|max:100',
            'designation' => 'required|string|max:255',
            'mesure' => 'nullable|string|max:50',
            'stock_initial' => 'required|numeric|min:0',
            'sortie' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif',
        ]);

        $article->update($validated);

        return redirect()->route('depot.iam.index')->with('success', 'Article corrigé avec succès.');
    }

    public function print(DepotIamArticle $article): View
    {
        return view('depot.iam-print', compact('article'));
    }

    public function exportPdf(DepotIamArticle $article): View
    {
        return view('depot.iam-export-pdf', compact('article'));
    }
}
