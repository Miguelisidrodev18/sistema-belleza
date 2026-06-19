@props([
    'cols' => '3',
    'gap' => '6',
    'responsive' => true,
    'stagger' => false,
])

@php
    $colsMap = [
        '1' => 'grid-cols-1',
        '2' => $responsive ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-2',
        '3' => $responsive ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3' : 'grid-cols-3',
        '4' => $responsive ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4' : 'grid-cols-4',
        '6' => $responsive ? 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-6' : 'grid-cols-6',
        'auto' => 'grid-cols-[repeat(auto-fit,minmax(280px,1fr))]',
    ];

    $classes = implode(' ', [
        'grid',
        $colsMap[$cols] ?? $colsMap['3'],
        "gap-{$gap}",
    ]);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
