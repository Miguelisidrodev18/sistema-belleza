@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'target' => '_self',
    'type' => 'button',
    'icon' => null,
    'iconRight' => null,
    'iconOnly' => false,
    'fullWidth' => false,
    'rounded' => 'default',
    'disabled' => false,
    'loading' => false,
    'external' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 cursor-pointer';

    $variants = [
        'primary' => 'bg-ugarte-primary text-white hover:bg-ugarte-primary-dark focus:ring-ugarte-primary shadow-button hover:shadow-button',
        'secondary' => 'bg-ugarte-secondary text-white hover:bg-ugarte-primary focus:ring-ugarte-secondary',
        'outline' => 'border-2 border-ugarte-primary text-ugarte-primary hover:bg-ugarte-primary hover:text-white focus:ring-ugarte-primary',
        'ghost' => 'text-ugarte-primary hover:bg-ugarte-primary-50 focus:ring-ugarte-primary',
        'whatsapp' => 'bg-[#25D366] text-white hover:bg-[#1DA851] focus:ring-[#25D366]',
        'white' => 'bg-white text-ugarte-primary hover:bg-ugarte-primary-50 focus:ring-white',
        'danger' => 'bg-error text-white hover:bg-red-600 focus:ring-error',
    ];

    $sizes = [
        'xs' => 'px-3 py-1.5 text-xs',
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3 text-base',
        'lg' => 'px-8 py-4 text-lg',
        'xl' => 'px-10 py-5 text-xl',
    ];

    $roundedMap = [
        'default' => 'rounded-lg',
        'full' => 'rounded-full',
        'none' => 'rounded-none',
    ];

    $classes = implode(' ', array_filter([
        $baseClasses,
        $variants[$variant] ?? $variants['primary'],
        $iconOnly ? 'p-3' : ($sizes[$size] ?? $sizes['md']),
        $roundedMap[$rounded] ?? $roundedMap['default'],
        $fullWidth ? 'w-full' : '',
        ($disabled || $loading) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '',
    ]));
@endphp

@if($href)
    <a
        href="{{ $href }}"
        target="{{ $external ? '_blank' : $target }}"
        @if($external) rel="noopener noreferrer" @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($loading)
            <svg class="mr-2 h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        @if($disabled || $loading) disabled @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($loading)
            <svg class="mr-2 h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif
