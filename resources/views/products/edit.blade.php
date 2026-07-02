@extends('layouts.app')

@section('title', 'Modifier article')
@section('page-title', 'Modifier : ' . $product->name)

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('products.update', $product) }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Référence *</label>
                <input type="text" name="reference" value="{{ old('reference', $product->reference) }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="category_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">— Aucune —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">{{ old('description', $product->description) }}</textarea>
        </div>
        <p class="text-sm text-gray-500">Stock actuel : <strong class="text-gds-navy">{{ $product->quantity }} {{ $product->unit }}</strong> — utilisez les mouvements de stock pour ajuster.</p>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Seuil alerte *</label>
                <input type="number" name="min_quantity" value="{{ old('min_quantity', $product->min_quantity) }}" min="0" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unité *</label>
                <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire</label>
                <input type="number" name="unit_price" value="{{ old('unit_price', $product->unit_price) }}" step="0.01" min="0"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Emplacement</label>
            <input type="text" name="location" value="{{ old('location', $product->location) }}"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-gds-navy text-white text-sm font-medium rounded-lg hover:bg-gds-navy-dark">Mettre à jour</button>
            <a href="{{ route('products.index') }}" class="px-6 py-2.5 text-sm text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
