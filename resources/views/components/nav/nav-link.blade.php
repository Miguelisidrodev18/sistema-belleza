@props([
    'href',
    'active' => false,
    'mobile' => false,
    'scrollTo' => true,
])

@if($mobile)
    <a
        href="{{ $href }}"
        @if($scrollTo) @click="close()" @endif
        {{ $attributes->merge(['class' => 'block px-4 py-3 text-base font-medium transition-colors ' . ($active ? 'text-ugarte-primary bg-ugarte-primary-50' : 'text-ugarte-black hover:text-ugarte-primary hover:bg-ugarte-primary-50')]) }}
    >
        {{ $slot }}
    </a>
@else
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => 'text-sm font-medium transition-colors duration-300 ' . ($active ? 'text-ugarte-primary' : 'text-ugarte-black/70 hover:text-ugarte-primary')]) }}
    >
        {{ $slot }}
    </a>
@endif
