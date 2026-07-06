@extends('layouts.app')

@section('title', 'Corriger article IAM')
@section('page-title', 'Corriger article IAM')
@section('page-subtitle', $article->designation)

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ route('depot.iam.update', $article) }}" class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Réf</label>
            <input type="text" name="reference" value="{{ old('reference', $article->reference) }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Désignation</label>
            <input type="text" name="designation" value="{{ old('designation', $article->designation) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Mesure</label>
            <input type="text" name="mesure" value="{{ old('mesure', $article->mesure) }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Stock Initial</label>
                <input type="number" name="stock_initial" value="{{ old('stock_initial', $article->stock_initial) }}" min="0" step="0.01" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Sortie</label>
                <input type="number" name="sortie" value="{{ old('sortie', $article->sortie) }}" min="0" step="0.01" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Statut</label>
            <select name="statut" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                @foreach(\App\Models\DepotIamArticle::STATUT_LABELS as $value => $label)
                    <option value="{{ $value }}" @selected(old('statut', $article->statut) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <p class="text-xs text-slate-500">Entrée : <strong>{{ number_format($article->entree, 2, ',', ' ') }}</strong> (calculée depuis les bons d'achat IAM)</p>
        <p class="text-xs text-slate-500">Stock final : <strong>{{ number_format($article->stockFinal(), 2, ',', ' ') }}</strong></p>
        <p class="text-xs text-slate-500">État : <strong>{{ $article->etatLabel() }}</strong> (calculé automatiquement selon le stock)</p>
        <div class="flex gap-2">
            <a href="{{ route('depot.iam.index') }}" class="px-4 py-2 text-sm rounded-lg border border-slate-200 text-slate-600">Retour</a>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white font-semibold">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
