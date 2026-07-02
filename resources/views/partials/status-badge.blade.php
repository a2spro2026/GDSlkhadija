@php
    $labels = \App\Models\Task::statusLabels();
    $colors = [
        'en_attente' => 'bg-amber-100 text-amber-700',
        'en_cours' => 'bg-blue-100 text-blue-700',
        'terminee' => 'bg-green-100 text-green-700',
        'annulee' => 'bg-gray-100 text-gray-600',
    ];
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$status] ?? 'bg-gray-100 text-gray-600' }}">
    {{ $labels[$status] ?? $status }}
</span>
