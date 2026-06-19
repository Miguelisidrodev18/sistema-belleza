@props([
    'label' => null,
    'cols' => '1',
])

@php
    $colsMap = [
        '1' => 'grid-cols-1',
        '2' => 'grid-cols-1 md:grid-cols-2',
        '3' => 'grid-cols-1 md:grid-cols-3',
    ];
@endphp

<fieldset {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @if($label)
        <legend class="text-sm font-semibold text-ugarte-black">{{ $label }}</legend>
    @endif

    <div class="grid gap-4 {{ $colsMap[$cols] ?? $colsMap['1'] }}">
        {{ $slot }}
    </div>
</fieldset>
