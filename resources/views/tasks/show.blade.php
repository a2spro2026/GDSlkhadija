@extends('layouts.app')

@section('title', $task->title)
@section('page-title', $task->title)

@section('header-actions')
    @if(auth()->user()->canManage())
        <a href="{{ route('tasks.edit', $task) }}" class="text-sm text-gds-teal hover:underline">Modifier</a>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
                @include('partials.priority-badge', ['priority' => $task->priority])
                @include('partials.status-badge', ['status' => $task->status])
            </div>

            @if($task->description)
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-2">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $task->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Technicien assigné</p>
                    <p class="font-medium text-gds-navy">{{ $task->assignee?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Créée par</p>
                    <p class="font-medium">{{ $task->creator?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Client</p>
                    <p class="font-medium">{{ $task->client_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Lieu</p>
                    <p class="font-medium">{{ $task->location ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Échéance</p>
                    <p class="font-medium">{{ $task->due_date?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Créée le</p>
                    <p class="font-medium">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            @if($task->technician_notes)
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-blue-700 mb-1">Notes du technicien</h3>
                    <p class="text-sm text-blue-900 whitespace-pre-line">{{ $task->technician_notes }}</p>
                </div>
            @endif
        </div>

        @if($task->materials->count())
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gds-navy mb-4">Matériel requis</h3>
            <table class="w-full text-sm">
                <thead><tr class="text-left text-gray-500">
                    <th class="pb-2">Article</th><th class="pb-2">Référence</th><th class="pb-2 text-right">Quantité</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($task->materials as $mat)
                        <tr>
                            <td class="py-2">{{ $mat->product->name }}</td>
                            <td class="py-2 text-gray-400 font-mono text-xs">{{ $mat->product->reference }}</td>
                            <td class="py-2 text-right font-medium">{{ $mat->quantity }} {{ $mat->product->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="space-y-4">
        @if(auth()->user()->isTechnician() && $task->assigned_to === auth()->id() && in_array($task->status, ['en_attente', 'en_cours']))
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gds-navy mb-4">Actions</h3>
            <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        @if($task->status === 'en_attente')
                            <option value="en_cours">Démarrer — En cours</option>
                        @endif
                        <option value="terminee">Marquer comme terminée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes d'intervention</label>
                    <textarea name="technician_notes" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" placeholder="Compte-rendu...">{{ $task->technician_notes }}</textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-gds-teal text-white text-sm font-medium rounded-lg hover:bg-gds-teal-dark">Mettre à jour</button>
            </form>
        </div>
        @endif

        @if(auth()->user()->canManage() && $task->status !== 'annulee')
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Supprimer cette tâche ?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 text-sm text-red-600 border border-red-200 rounded-lg hover:bg-red-50">Supprimer la tâche</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
