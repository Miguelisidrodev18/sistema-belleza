@props([
    'id' => null,
    'bg' => 'white',
    'padding' => 'default',
    'overflow' => 'hidden',
    'animated' => true,
    'fullWidth' => false,
    'tag' => 'section',
])

@php
    $bgMap = [
        'white' => 'bg-white',
        'light' => 'bg-ugarte-bg',
        'warm' => 'bg-ugarte-bg-warm',
        'primary' => 'bg-ugarte-primary text-white',
        'dark' => 'bg-ugarte-black text-white',
        'gradient' => 'bg-gradient-to-br from-ugarte-primary to-ugarte-secondary text-white',
        'none' => '',
    ];

    $paddingMap = [
        'none' => '',
        'sm' => 'section-padding-sm',
        'default' => 'section-padding',
        'lg' => 'section-padding-lg',
    ];

    $classes = implode(' ', array_filter([
        'relative',
        $bgMap[$bg] ?? $bgMap['white'],
        $paddingMap[$padding] ?? $paddingMap['default'],
        $overflow === 'hidden' ? 'overflow-hidden' : '',
    ]));
@endphp

<{{ $tag }}
    @if($id) id="{{ $id }}" @endif
    @if($animated) x-data="lazySection" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($animated)
        <div
            class="transition-all duration-700 ease-out"
            :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        >
    @endif

    @if(!$fullWidth)
        <x-ui.container>
            {{ $slot }}
        </x-ui.container>
    @else
        {{ $slot }}
    @endif

    @if($animated)
        </div>
    @endif
</{{ $tag }}>
