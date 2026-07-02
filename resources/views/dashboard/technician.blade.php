@extends('layouts.app')

@section('title', 'Mon espace')
@section('page-title', 'Mon espace technicien')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')
<div class="stats-row cols-3">
    <div class="stat-card">
        <p class="stat-label">En attente</p>
        <p class="stat-value" style="color:#d97706">{{ $stats['pending'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">En cours</p>
        <p class="stat-value" style="color:#2563EB">{{ $stats['in_progress'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Terminées ce mois</p>
        <p class="stat-value" style="color:#059669">{{ $stats['completed_month'] }}</p>
    </div>
</div>

<div class="content-panel">
    <div class="content-panel-header">
        <h2 class="font-poppins font-semibold text-[#071A35] text-sm m-0">Mes interventions</h2>
    </div>

    @if($myTasks->count())
        <div class="divide-y divide-gray-100">
            @foreach($myTasks as $task)
                <div class="px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                @include('partials.priority-badge', ['priority' => $task->priority])
                                @include('partials.status-badge', ['status' => $task->status])
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                            @if($task->description)
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $task->description }}</p>
                            @endif
                            <div class="flex flex-wrap gap-4 mt-2 text-xs text-gray-400">
                                @if($task->client_name)
                                    <span>Client : {{ $task->client_name }}</span>
                                @endif
                                @if($task->location)
                                    <span>Lieu : {{ $task->location }}</span>
                                @endif
                                @if($task->due_date)
                                    <span>Échéance : {{ $task->due_date->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 shrink-0">
                            <a href="{{ route('tasks.show', $task) }}" class="text-sm text-gds-teal hover:underline">Détails</a>
                            @if($task->status === 'en_attente')
                                <form method="POST" action="{{ route('tasks.update', $task) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="en_cours">
                                    <button type="submit" class="text-sm bg-gds-teal text-white px-3 py-1.5 rounded-lg hover:bg-gds-teal-dark transition">
                                        Démarrer
                                    </button>
                                </form>
                            @elseif($task->status === 'en_cours')
                                <form method="POST" action="{{ route('tasks.update', $task) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="terminee">
                                    <button type="submit" class="text-sm bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition">
                                        Terminer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-400">Aucune tâche assignée pour le moment.</p>
        </div>
    @endif
</div>
@endsection
