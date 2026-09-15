@props(['type' => 'success'])

@php
    $styles = [
        'success' => 'background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;',
        'error'   => 'background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;',
        'warning' => 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a;',
    ];
@endphp

<div style="{{ $styles[$type] ?? $styles['success'] }} padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem;">
    {{ $slot }}
</div>