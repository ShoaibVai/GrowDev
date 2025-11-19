@props([
    'type' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'href' => null,
])

@php
    $baseClasses = 'btn';
    $typeClasses = 'btn-' . $type;
    $sizeClasses = 'btn-' . $size;
    $classes = trim($baseClasses . ' ' . $typeClasses . ' ' . $sizeClasses);
    
    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed';
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
