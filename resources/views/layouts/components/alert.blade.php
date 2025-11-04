@props([
    'type' => 'info',  // success, danger, warning, info
    'dismissible' => true,
    'icon' => null
])

@php
    // Mapper les types aux icônes
    $icons = [
        'success' => '✅',
        'danger' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️'
    ];
    $displayIcon = $icon ?? $icons[$type] ?? 'ℹ️';
@endphp

<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    <strong>{{ $displayIcon }}</strong> {{ $slot }}
    
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    @endif
</div>