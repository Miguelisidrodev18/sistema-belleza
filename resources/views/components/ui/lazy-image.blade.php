@props([
    'src',
    'alt',
    'width' => null,
    'height' => null,
    'aspect' => null,
    'rounded' => 'lg',
    'objectFit' => 'cover',
    'placeholder' => 'skeleton',
    'eager' => false,
])

@php
    $aspectMap = [
        'video' => 'aspect-video',
        'square' => 'aspect-square',
        'portrait' => 'aspect-[3/4]',
        'wide' => 'aspect-[21/9]',
    ];

    $roundedMap = [
        'none' => '',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        'full' => 'rounded-full',
    ];

    $fitMap = [
        'cover' => 'object-cover',
        'contain' => 'object-contain',
        'fill' => 'object-fill',
    ];

    $classes = implode(' ', array_filter([
        'w-full',
        $aspectMap[$aspect] ?? '',
        $roundedMap[$rounded] ?? $roundedMap['lg'],
        $fitMap[$objectFit] ?? $fitMap['cover'],
    ]));
@endphp

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    @if(!$eager) loading="lazy" @endif
    decoding="async"
    {{ $attributes->merge(['class' => $classes]) }}
>
