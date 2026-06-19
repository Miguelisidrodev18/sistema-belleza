@props([
    'links' => null,
    'size' => 'md',
    'color' => 'primary',
    'gap' => '3',
])

@php
    $socialLinks = $links ?? config('site.social', []);
@endphp

<div {{ $attributes->merge(['class' => "flex items-center gap-{$gap}"]) }}>
    @foreach($socialLinks as $platform => $url)
        @if($url)
            <x-branding.social-link
                :platform="$platform"
                :url="$url"
                :size="$size"
                :color="$color"
            />
        @endif
    @endforeach
</div>
