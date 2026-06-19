@props([
    'size' => 'base',
    'color' => 'default',
    'weight' => 'normal',
    'leading' => 'relaxed',
    'as' => 'p',
    'maxWidth' => null,
])

@php
    $sizeMap = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
    ];

    $colorMap = [
        'default' => 'text-gray-700',
        'muted' => 'text-gray-500',
        'primary' => 'text-ugarte-primary',
        'white' => 'text-white',
        'inherit' => '',
    ];

    $weightMap = [
        'normal' => 'font-normal',
        'medium' => 'font-medium',
        'semibold' => 'font-semibold',
    ];

    $leadingMap = [
        'tight' => 'leading-tight',
        'normal' => 'leading-normal',
        'relaxed' => 'leading-relaxed',
        'loose' => 'leading-loose',
    ];

    $maxWidthMap = [
        'prose' => 'max-w-prose',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
    ];

    $classes = implode(' ', array_filter([
        $sizeMap[$size] ?? $sizeMap['base'],
        $colorMap[$color] ?? $colorMap['default'],
        $weightMap[$weight] ?? $weightMap['normal'],
        $leadingMap[$leading] ?? $leadingMap['relaxed'],
        $maxWidth ? ($maxWidthMap[$maxWidth] ?? '') : '',
    ]));
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $as }}>
