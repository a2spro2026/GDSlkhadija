@php
    $labels = \App\Models\Task::priorityLabels();
    $colors = [
        'basse' => 'bg-gray-100 text-gray-600',
        'normale' => 'bg-blue-50 text-blue-600',
        'haute' => 'bg-orange-100 text-orange-700',
        'urgente' => 'bg-red-100 text-red-700',
    ];
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$priority] ?? 'bg-gray-100 text-gray-600' }}">
    {{ $labels[$priority] ?? $priority }}
</span>
