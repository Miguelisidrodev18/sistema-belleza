@props([
    'src' => null,
    'name',
    'size' => 'md',
    'rounded' => 'full',
    'ring' => false,
    'ringColor' => 'primary',
])

@php
    $sizeMap = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-12 w-12 text-sm',
        'lg' => 'h-16 w-16 text-base',
        'xl' => 'h-20 w-20 text-lg',
    ];

    $roundedMap = [
        'full' => 'rounded-full',
        'lg' => 'rounded-lg',
    ];

    $ringClasses = $ring ? 'ring-2 ring-offset-2 ring-ugarte-primary' : '';

    $initials = collect(explode(' ', $name))
        ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->take(2)
        ->implode('');

    $classes = implode(' ', array_filter([
        'inline-flex items-center justify-center shrink-0 overflow-hidden',
        $sizeMap[$size] ?? $sizeMap['md'],
        $roundedMap[$rounded] ?? $roundedMap['full'],
        $ringClasses,
    ]));
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($src)
        <img
            src="{{ $src }}"
            alt="{{ $name }}"
            class="h-full w-full object-cover"
            loading="lazy"
        >
    @else
        <span class="flex h-full w-full items-center justify-center bg-gradient-to-br from-ugarte-primary to-ugarte-secondary font-semibold text-white">
            {{ $initials }}
        </span>
    @endif
</div>
