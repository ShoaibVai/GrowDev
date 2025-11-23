@props(['title' => '', 'value' => '', 'icon' => null, 'color' => 'indigo'])

@php
    $bgClass = 'bg-indigo-100';
    $textClass = 'text-indigo-600';
    switch ($color) {
        case 'green':
            $bgClass = 'bg-green-100';
            $textClass = 'text-green-600';
            break;
        case 'yellow':
            $bgClass = 'bg-yellow-100';
            $textClass = 'text-yellow-600';
            break;
        case 'purple':
            $bgClass = 'bg-purple-100';
            $textClass = 'text-purple-600';
            break;
        default:
            $bgClass = 'bg-indigo-100';
            $textClass = 'text-indigo-600';
            break;
    }
@endphp

<div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
    <div class="flex-shrink-0">
        <div class="h-12 w-12 rounded-full {{ $bgClass }} {{ $textClass }} flex items-center justify-center text-lg">
            {!! $icon ?? '' !!}
        </div>
    </div>
    <div>
        <div class="text-sm text-gray-500">{{ $title }}</div>
        <div class="text-2xl font-semibold text-gray-900">{{ $value }}</div>
    </div>
</div>
