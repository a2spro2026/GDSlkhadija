@extends('layouts.app')

@section('title', 'Nouvel article')
@section('page-title', 'Ajouter un article')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('products.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Référence *</label>
                <input type="text" name="reference" value="{{ old('reference') }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gds-teal/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="category_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">— Aucune —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'article *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gds-teal/50">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantité initiale *</label>
                <input type="number" name="quantity" value="{{ old('quantity', 0) }}" min="0" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Seuil alerte *</label>
                <input type="number" name="min_quantity" value="{{ old('min_quantity', 5) }}" min="0" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unité *</label>
                <input type="text" name="unit" value="{{ old('unit', 'unité') }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Emplacement</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Ex: Rack A-12"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (MAD)</label>
                <input type="number" name="unit_price" value="{{ old('unit_price') }}" step="0.01" min="0"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-gds-navy text-white text-sm font-medium rounded-lg hover:bg-gds-navy-dark">Enregistrer</button>
            <a href="{{ route('products.index') }}" class="px-6 py-2.5 text-sm text-gray-600 hover:text-gray-900">Annuler</a>
        </div>
    </form>
</div>
@endsection
