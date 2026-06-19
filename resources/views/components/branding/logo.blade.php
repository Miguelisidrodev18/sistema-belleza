@props([
    'variant' => 'full',
    'size' => 'md',
    'color' => 'default',
    'href' => '/',
])

@php
    $sizeMap = [
        'sm' => 'h-10',
        'md' => 'h-12',
        'lg' => 'h-16',
    ];

    $logoSrc = match($color) {
        'white' => '/images/logo/logo-ugarte-blanco.png',
        default => '/images/logo/logo-ugarte-color.png',
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center shrink-0']) }}>
    <img
        src="{{ $logoSrc }}"
        alt="{{ config('site.name') }} — {{ config('site.tagline') }}"
        class="{{ $sizeMap[$size] ?? $sizeMap['md'] }} w-auto object-contain"
    >
</a>
