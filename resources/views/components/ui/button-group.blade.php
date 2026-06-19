@props([
    'direction' => 'row',
    'align' => 'start',
    'gap' => '4',
    'responsive' => true,
])

@php
    $classes = implode(' ', array_filter([
        'flex',
        $responsive ? 'flex-col sm:flex-row' : ($direction === 'col' ? 'flex-col' : 'flex-row'),
        "items-{$align}",
        "gap-{$gap}",
    ]));
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
