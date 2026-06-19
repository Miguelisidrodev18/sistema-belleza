@props([
    'variant' => 'default',
    'padding' => 'md',
    'rounded' => 'xl',
    'hover' => false,
    'href' => null,
    'accentColor' => null,
    'accentPosition' => 'top',
    'overflow' => true,
])

@php
    $variantMap = [
        'default' => 'bg-white shadow-card',
        'elevated' => 'bg-white shadow-card-hover',
        'bordered' => 'bg-white border border-ugarte-border',
        'glass' => 'bg-white/80 backdrop-blur-md shadow-card border border-white/20',
        'flat' => 'bg-ugarte-bg',
    ];

    $paddingMap = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];

    $roundedMap = [
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
    ];

    $classes = implode(' ', array_filter([
        'relative',
        $variantMap[$variant] ?? $variantMap['default'],
        $roundedMap[$rounded] ?? $roundedMap['xl'],
        $overflow ? 'overflow-hidden' : '',
        $hover ? 'transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover' : '',
    ]));
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes . ' block']) }}>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
@endif

    @if($accentColor)
        @if($accentPosition === 'top')
            <div class="absolute inset-x-0 top-0 h-1 rounded-t-xl" style="background-color: {{ $accentColor }}"></div>
        @else
            <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl" style="background-color: {{ $accentColor }}"></div>
        @endif
    @endif

    @if(isset($header))
        <div class="{{ $overflow ? '' : '' }}">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $paddingMap[$padding] ?? $paddingMap['md'] }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="border-t border-gray-100 {{ $paddingMap[$padding] ?? $paddingMap['md'] }}">
            {{ $footer }}
        </div>
    @endif

@if($href)
    </a>
@else
    </div>
@endif
