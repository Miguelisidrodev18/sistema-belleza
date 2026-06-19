@props([
    'size' => 'default',
    'padding' => true,
])

@php
    $sizeMap = [
        'sm' => 'max-w-4xl',
        'default' => 'max-w-7xl',
        'lg' => 'max-w-[90rem]',
        'full' => 'max-w-full',
    ];

    $classes = implode(' ', array_filter([
        'mx-auto',
        $sizeMap[$size] ?? $sizeMap['default'],
        $padding ? 'px-4 sm:px-6 lg:px-8' : '',
    ]));
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
