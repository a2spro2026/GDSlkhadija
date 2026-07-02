@extends('layouts.app')

@section('title', 'Tâches')
@section('page-title', auth()->user()->isTechnician() ? 'Mes Tâches' : 'Tâches & Interventions')

@section('header-actions')
    @if(auth()->user()->canManage())
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 bg-gds-navy text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-gds-navy-dark transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouvelle tâche
        </a>
    @endif
@endsection

@section('content')
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Statut</label>
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">Tous</option>
            @foreach(\App\Models\Task::statusLabels() as $key => $label)
                <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Priorité</label>
        <select name="priority" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">Toutes</option>
            @foreach(\App\Models\Task::priorityLabels() as $key => $label)
                <option value="{{ $key }}" @selected(request('priority') == $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    @if(auth()->user()->canManage())
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Technicien</label>
        <select name="technician" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">Tous</option>
            @foreach($technicians as $tech)
                <option value="{{ $tech->id }}" @selected(request('technician') == $tech->id)>{{ $tech->name }}</option>
            @endforeach
        </select>
    </div>
    @endif
    <button type="submit" class="px-4 py-2 bg-gds-navy text-white text-sm rounded-lg">Filtrer</button>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Tâche</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Technicien</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Client / Lieu</th>
                <th class="text-center px-6 py-3 font-semibold text-gray-600">Priorité</th>
                <th class="text-center px-6 py-3 font-semibold text-gray-600">Statut</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Échéance</th>
                <th class="text-right px-6 py-3 font-semibold text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($tasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gds-navy hover:underline">{{ $task->title }}</a>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $task->assignee?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        <p>{{ $task->client_name ?? '—' }}</p>
                        @if($task->location)<p class="text-xs text-gray-400">{{ $task->location }}</p>@endif
                    </td>
                    <td class="px-6 py-4 text-center">@include('partials.priority-badge', ['priority' => $task->priority])</td>
                    <td class="px-6 py-4 text-center">@include('partials.status-badge', ['status' => $task->status])</td>
                    <td class="px-6 py-4 text-gray-500">{{ $task->due_date?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('tasks.show', $task) }}" class="text-xs text-gds-teal hover:underline">Voir</a>
                        @if(auth()->user()->canManage())
                            <a href="{{ route('tasks.edit', $task) }}" class="text-xs text-gray-500 hover:underline ml-2">Modifier</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">Aucune tâche trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($tasks->hasPages())
        <div class="px-6 py-4 border-t">{{ $tasks->links() }}</div>
    @endif
</div>
@endsection
