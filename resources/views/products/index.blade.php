@extends('layouts.app')

@section('title', 'Gestion Stock')
@section('page-title', 'Gestion Stock')
@section('page-subtitle', 'Inventaire des équipements et consommables')

@section('header-actions')
    <a href="{{ route('products.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Ajouter un article
    </a>
@endsection

@section('content')
<div class="content-panel p-4 mb-5">
<form method="GET" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Rechercher</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou référence..."
               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gds-teal/50 focus:border-gds-teal">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Catégorie</label>
        <select name="category" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">Toutes</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <label class="flex items-center gap-2 text-sm text-gray-600 pb-2">
        <input type="checkbox" name="low_stock" value="1" @checked(request('low_stock')) class="rounded text-gds-teal">
        Stock faible uniquement
    </label>
    <button type="submit" class="btn-primary">Filtrer</button>
</form>
</div>

<div class="content-panel overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Référence</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Article</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Catégorie</th>
                <th class="text-center px-6 py-3 font-semibold text-gray-600">Quantité</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Emplacement</th>
                <th class="text-right px-6 py-3 font-semibold text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50 {{ $product->isLowStock() ? 'bg-red-50/50' : '' }}">
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $product->reference }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                        @if($product->isLowStock())
                            <span class="text-xs text-red-600 font-medium">Stock faible</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold {{ $product->isLowStock() ? 'text-red-600' : 'text-gds-navy' }}">
                            {{ $product->quantity }}
                        </span>
                        <span class="text-gray-400 text-xs">{{ $product->unit }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->location ?? '—' }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="document.getElementById('movement-{{ $product->id }}').classList.toggle('hidden')"
                                    class="text-xs text-gds-teal hover:underline">Mouvement</button>
                            <a href="{{ route('products.edit', $product) }}" class="text-xs text-gray-500 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Supprimer cet article ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Suppr.</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <tr id="movement-{{ $product->id }}" class="hidden bg-gray-50">
                    <td colspan="6" class="px-6 py-4">
                        <form method="POST" action="{{ route('products.movement', $product) }}" class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Type</label>
                                <select name="type" class="px-3 py-1.5 border rounded-lg text-sm">
                                    <option value="entree">Entrée</option>
                                    <option value="sortie">Sortie</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Quantité</label>
                                <input type="number" name="quantity" min="1" required class="w-24 px-3 py-1.5 border rounded-lg text-sm">
                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <label class="block text-xs text-gray-500 mb-1">Motif</label>
                                <input type="text" name="reason" placeholder="Ex: Réception fournisseur" class="w-full px-3 py-1.5 border rounded-lg text-sm">
                            </div>
                            <button type="submit" class="px-4 py-1.5 bg-gds-teal text-white text-sm rounded-lg hover:bg-gds-teal-dark">Enregistrer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Aucun article en stock.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
    @endif
</div>
@endsection
