@extends('layouts.app')

@section('title', 'Nouvelle tâche')
@section('page-title', 'Créer une intervention')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('tasks.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre de l'intervention *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Ex: Installation switch Cisco — Client XYZ"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gds-teal/50">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" placeholder="Détails de l'intervention...">{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigner à *</label>
                <select name="assigned_to" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">— Choisir un technicien —</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" @selected(old('assigned_to') == $tech->id)>{{ $tech->name }} ({{ $tech->username }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priorité *</label>
                <select name="priority" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    @foreach(\App\Models\Task::priorityLabels() as $key => $label)
                        <option value="{{ $key }}" @selected(old('priority', 'normale') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <input type="text" name="client_name" value="{{ old('client_name') }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu d'intervention</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Ex: Datacenter Casablanca"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}"
                   class="w-full max-w-xs px-3 py-2 border border-gray-200 rounded-lg text-sm">
        </div>

        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Matériel nécessaire (optionnel)</h3>
            <div id="materials-list" class="space-y-2">
                <div class="flex gap-3 items-center material-row">
                    <select name="materials[0][product_id]" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">— Article —</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->quantity }} dispo.)</option>
                        @endforeach
                    </select>
                    <input type="number" name="materials[0][quantity]" min="1" placeholder="Qté" class="w-20 px-3 py-2 border border-gray-200 rounded-lg text-sm">
                </div>
            </div>
            <button type="button" onclick="addMaterialRow()" class="mt-2 text-sm text-gds-teal hover:underline">+ Ajouter un article</button>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-gds-navy text-white text-sm font-medium rounded-lg hover:bg-gds-navy-dark">Créer et assigner</button>
            <a href="{{ route('tasks.index') }}" class="px-6 py-2.5 text-sm text-gray-600">Annuler</a>
        </div>
    </form>
</div>

<script>
let materialIndex = 1;
function addMaterialRow() {
    const list = document.getElementById('materials-list');
    const row = document.querySelector('.material-row').cloneNode(true);
    row.querySelectorAll('select, input').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${materialIndex}]`);
        el.value = '';
    });
    list.appendChild(row);
    materialIndex++;
}
</script>
@endsection
