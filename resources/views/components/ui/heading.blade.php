@props([
    'level' => 2,
    'size' => null,
    'weight' => 'bold',
    'color' => 'default',
    'align' => null,
    'as' => null,
])

@php
    $tag = $as ?? 'h' . $level;

    $sizeMap = [
        'hero' => 'text-4xl sm:text-5xl md:text-6xl lg:text-7xl',
        '2xl' => 'text-3xl sm:text-4xl md:text-5xl',
        'xl' => 'text-2xl sm:text-3xl md:text-4xl',
        'lg' => 'text-xl sm:text-2xl md:text-3xl',
        'md' => 'text-lg sm:text-xl md:text-2xl',
        'sm' => 'text-base sm:text-lg',
        'xs' => 'text-sm sm:text-base',
    ];

    $defaultSizes = [
        1 => '2xl',
        2 => 'xl',
        3 => 'lg',
        4 => 'md',
        5 => 'sm',
        6 => 'xs',
    ];

    $resolvedSize = $size ?? ($defaultSizes[$level] ?? 'md');

    $weightMap = [
        'normal' => 'font-normal',
        'medium' => 'font-medium',
        'semibold' => 'font-semibold',
        'bold' => 'font-bold',
        'extrabold' => 'font-extrabold',
    ];

    $colorMap = [
        'default' => 'text-ugarte-black',
        'primary' => 'text-ugarte-primary',
        'secondary' => 'text-ugarte-secondary',
        'white' => 'text-white',
        'inherit' => '',
    ];

    $alignMap = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];

    $classes = implode(' ', array_filter([
        $sizeMap[$resolvedSize] ?? $sizeMap['md'],
        $weightMap[$weight] ?? $weightMap['bold'],
        $colorMap[$color] ?? $colorMap['default'],
        $align ? ($alignMap[$align] ?? '') : '',
        'tracking-tight',
    ]));
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
