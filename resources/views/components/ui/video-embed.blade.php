@props([
    'url',
    'title' => 'Video',
    'aspect' => 'video',
])

@php
    $aspectMap = [
        'video' => 'aspect-video',
        'square' => 'aspect-square',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl ' . ($aspectMap[$aspect] ?? $aspectMap['video'])]) }}>
    <iframe
        src="{{ $url }}"
        title="{{ $title }}"
        class="h-full w-full"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
        loading="lazy"
    ></iframe>
</div>
