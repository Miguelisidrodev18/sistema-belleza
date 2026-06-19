@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'url' => null,
    'locale' => null,
])

@php
    $ogTitle = $title ?? config('site.seo.default_title');
    $ogDescription = $description ?? config('site.seo.default_description');
    $ogImage = $image ?? config('site.seo.default_image');
    $ogUrl = $url ?? url()->current();
    $ogLocale = $locale ?? config('site.seo.locale', 'es_PE');
@endphp

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:url" content="{{ $ogUrl }}">
<meta property="og:site_name" content="{{ config('site.name') }}">
<meta property="og:locale" content="{{ $ogLocale }}">
@if($ogImage)
    <meta property="og:image" content="{{ url($ogImage) }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
@if($ogImage)
    <meta name="twitter:image" content="{{ url($ogImage) }}">
@endif
