@props(['variant' => 'primary', 'type' => 'submit', 'href' => null])

@php
    $colors = [
        'primary' => 'background: #2563eb; color: white;',
        'success' => 'background: #16a34a; color: white;',
        'danger'  => 'background: #ef4444; color: white;',
        'secondary' => 'background: #9ca3af; color: white;',
    ];
    $baseStyle = 'padding: 8px 16px; border-radius: 6px; font-weight: bold; text-decoration: none; display: inline-block; border: none; cursor: pointer; font-size: 0.85rem;';
@endphp

@if($href)
    <a href="{{ $href }}" style="{{ $baseStyle }} {{ $colors[$variant] ?? $colors['primary'] }}">
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" style="{{ $baseStyle }} {{ $colors[$variant] ?? $colors['primary'] }}">
        {{ $slot }}
    </button>
@endif