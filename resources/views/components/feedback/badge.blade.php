@props([
    'variant' => 'default',
    'size' => 'md',
    'color' => null,
    'dot' => false,
    'rounded' => 'full',
    'icon' => null,
])

@php
    $variantMap = [
        'default' => 'bg-gray-100 text-gray-700',
        'primary' => 'bg-ugarte-primary-50 text-ugarte-primary',
        'success' => 'bg-green-100 text-green-700',
        'warning' => 'bg-yellow-100 text-yellow-700',
        'danger' => 'bg-red-100 text-red-700',
        'info' => 'bg-blue-100 text-blue-700',
    ];

    $sizeMap = [
        'xs' => 'px-2 py-0.5 text-[10px]',
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
    ];

    $roundedMap = [
        'full' => 'rounded-full',
        'md' => 'rounded-md',
    ];

    $classes = implode(' ', array_filter([
        'inline-flex items-center gap-1.5 font-medium',
        $variantMap[$variant] ?? $variantMap['default'],
        $sizeMap[$size] ?? $sizeMap['md'],
        $roundedMap[$rounded] ?? $roundedMap['full'],
    ]));
@endphp

<span
    {{ $attributes->merge(['class' => $classes]) }}
    @if($color) style="background-color: {{ $color }}20; color: {{ $color }}" @endif
>
    @if($dot)
        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    @endif
    {{ $slot }}
</span>
