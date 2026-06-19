@props([
    'variant' => 'line',
    'color' => 'border',
    'spacing' => 'md',
])

@php
    $spacingMap = [
        'sm' => 'my-4',
        'md' => 'my-8',
        'lg' => 'my-12',
    ];

    $colorMap = [
        'border' => 'border-ugarte-border',
        'primary' => 'border-ugarte-primary/20',
        'white' => 'border-white/20',
    ];
@endphp

@if($variant === 'gradient')
    <div {{ $attributes->merge(['class' => $spacingMap[$spacing] ?? $spacingMap['md']]) }}>
        <div class="h-px bg-gradient-to-r from-transparent via-ugarte-primary/20 to-transparent"></div>
    </div>
@elseif($variant === 'dots')
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center gap-2 ' . ($spacingMap[$spacing] ?? $spacingMap['md'])]) }}>
        <span class="h-1.5 w-1.5 rounded-full bg-ugarte-primary/30"></span>
        <span class="h-1.5 w-1.5 rounded-full bg-ugarte-primary/30"></span>
        <span class="h-1.5 w-1.5 rounded-full bg-ugarte-primary/30"></span>
    </div>
@else
    <hr {{ $attributes->merge(['class' => ($colorMap[$color] ?? $colorMap['border']) . ' ' . ($spacingMap[$spacing] ?? $spacingMap['md'])]) }}>
@endif
