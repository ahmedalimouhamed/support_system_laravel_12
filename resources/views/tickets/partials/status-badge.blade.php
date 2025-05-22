@props(['status'])

@php
    $colors = [
        'open' => 'bg-blue-100 text-blue-800',
        'in_progress' => 'bg-yellow-100 text-yellow-800',
        'resolved' => 'bg-green-100 text-green-800',
        'closed' => 'bg-red-100 text-red-800',
    ];

    $labels = [
        'open' => 'Ouvert',
        'in_progress' => 'En cours',
        'resolved' => 'Résolu',
        'closed' => 'Fermer',
    ];
@endphp

<span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colors[$status] }}">
    {{ $labels[$status] }}
</span>