@extends('layouts.app')

@section('title', 'Modifier tâche')
@section('page-title', 'Modifier : ' . $task->title)

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('tasks.update', $task) }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">{{ old('description', $task->description) }}</textarea>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Technicien *</label>
                <select name="assigned_to" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" @selected(old('assigned_to', $task->assigned_to) == $tech->id)>{{ $tech->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priorité *</label>
                <select name="priority" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    @foreach(\App\Models\Task::priorityLabels() as $key => $label)
                        <option value="{{ $key }}" @selected(old('priority', $task->priority) == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                <select name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    @foreach(\App\Models\Task::statusLabels() as $key => $label)
                        <option value="{{ $key }}" @selected(old('status', $task->status) == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <input type="text" name="client_name" value="{{ old('client_name', $task->client_name) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                <input type="text" name="location" value="{{ old('location', $task->location) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Échéance</label>
            <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" class="w-full max-w-xs px-3 py-2 border border-gray-200 rounded-lg text-sm">
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-gds-navy text-white text-sm font-medium rounded-lg hover:bg-gds-navy-dark">Enregistrer</button>
            <a href="{{ route('tasks.show', $task) }}" class="px-6 py-2.5 text-sm text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
