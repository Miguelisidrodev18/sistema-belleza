@props([
    'size' => 'md',
    'color' => 'primary',
    'rounded' => 'xl',
    'variant' => 'filled',
])

@php
    $sizeMap = [
        'sm' => 'h-10 w-10',
        'md' => 'h-14 w-14',
        'lg' => 'h-[72px] w-[72px]',
    ];

    $variantColors = [
        'filled' => [
            'primary' => 'bg-ugarte-primary text-white',
            'secondary' => 'bg-ugarte-secondary text-white',
            'light' => 'bg-ugarte-primary-50 text-ugarte-primary',
            'white' => 'bg-white text-ugarte-primary',
        ],
        'outlined' => [
            'primary' => 'border-2 border-ugarte-primary text-ugarte-primary',
            'secondary' => 'border-2 border-ugarte-secondary text-ugarte-secondary',
            'light' => 'border-2 border-ugarte-border text-ugarte-primary',
            'white' => 'border-2 border-white text-white',
        ],
        'ghost' => [
            'primary' => 'text-ugarte-primary',
            'secondary' => 'text-ugarte-secondary',
            'light' => 'text-ugarte-primary/60',
            'white' => 'text-white',
        ],
    ];

    $roundedMap = [
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        'full' => 'rounded-full',
    ];

    $colorClasses = $variantColors[$variant][$color] ?? $variantColors['filled']['primary'];

    $classes = implode(' ', [
        'inline-flex items-center justify-center shrink-0',
        $sizeMap[$size] ?? $sizeMap['md'],
        $roundedMap[$rounded] ?? $roundedMap['xl'],
        $colorClasses,
    ]);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
