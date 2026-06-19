@props([
    'align' => 'right',
    'width' => '48',
])

@php
    $alignMap = [
        'left' => 'left-0 origin-top-left',
        'right' => 'right-0 origin-top-right',
    ];
@endphp

<div x-data="{ open: false }" @click.outside="open = false" {{ $attributes->merge(['class' => 'relative']) }}>
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @keydown.escape.window="open = false"
        class="absolute z-50 mt-2 w-{{ $width }} rounded-xl bg-white shadow-card-hover ring-1 ring-black/5 {{ $alignMap[$align] ?? $alignMap['right'] }}"
        x-cloak
    >
        {{ $content }}
    </div>
</div>
