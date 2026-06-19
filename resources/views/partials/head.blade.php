<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- SEO Meta --}}
<x-seo.meta
    :title="$seoTitle ?? null"
    :description="$seoDescription ?? null"
/>

{{-- Open Graph --}}
<x-seo.open-graph
    :title="$seoTitle ?? null"
    :description="$seoDescription ?? null"
    :image="$seoImage ?? null"
/>

{{-- Structured Data --}}
<x-seo.structured-data />

{{-- Google Fonts: Poppins (alternativa temporal a Gilroy) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

{{-- Favicon --}}
<link rel="icon" type="image/x-icon" href="/favicon.ico">

{{-- Vite Assets --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
