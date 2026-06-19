@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'canonical' => null,
    'robots' => 'index, follow',
])

@php
    $metaTitle = $title ?? config('site.seo.default_title');
    $metaDescription = $description ?? config('site.seo.default_description');
    $metaKeywords = $keywords ?? config('site.seo.keywords');
    $metaCanonical = $canonical ?? url()->current();
@endphp

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
@if($metaKeywords)
    <meta name="keywords" content="{{ $metaKeywords }}">
@endif
<meta name="robots" content="{{ $robots }}">
<meta name="author" content="{{ config('site.name') }}">
<link rel="canonical" href="{{ $metaCanonical }}">
