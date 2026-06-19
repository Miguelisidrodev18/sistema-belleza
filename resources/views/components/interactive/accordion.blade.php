@props([
    'items' => [],
    'multiple' => false,
    'defaultOpen' => null,
    'variant' => 'default',
])

@php
    $variantMap = [
        'default' => '',
        'bordered' => 'space-y-3',
        'separated' => 'space-y-4',
    ];
@endphp

<div
    x-data="accordion({{ $multiple ? 'true' : 'false' }})"
    x-init="@if($defaultOpen !== null) toggle({{ $defaultOpen }}) @endif"
    {{ $attributes->merge(['class' => $variantMap[$variant] ?? '']) }}
>
    @foreach($items as $index => $item)
        <x-interactive.accordion-item
            :index="$index"
            :question="$item['question']"
            :variant="$variant"
        >
            {{ $item['answer'] }}
        </x-interactive.accordion-item>
    @endforeach

    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>
