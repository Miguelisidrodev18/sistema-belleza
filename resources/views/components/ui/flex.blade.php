@props([
    'direction' => 'row',
    'items' => 'center',
    'justify' => 'start',
    'gap' => '4',
    'wrap' => false,
    'responsive' => false,
])

@php
    $classes = implode(' ', array_filter([
        'flex',
        $responsive ? 'flex-col md:flex-row' : ($direction === 'col' ? 'flex-col' : 'flex-row'),
        "items-{$items}",
        "justify-{$justify}",
        "gap-{$gap}",
        $wrap ? 'flex-wrap' : '',
    ]));
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
