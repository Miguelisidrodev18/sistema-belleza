@props([
    'size' => 'sm',
    'color' => 'primary',
    'uppercase' => true,
    'as' => 'span',
])

@php
    $sizeMap = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
    ];

    $colorMap = [
        'muted' => 'text-gray-500',
        'primary' => 'text-ugarte-secondary',
        'white' => 'text-white/80',
        'inherit' => '',
    ];

    $classes = implode(' ', array_filter([
        $sizeMap[$size] ?? $sizeMap['sm'],
        $colorMap[$color] ?? $colorMap['primary'],
        'font-semibold',
        $uppercase ? 'uppercase tracking-wider' : '',
    ]));
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $as }}>
