@props([
    'title' => null,
    'description' => null,
])

@php
    $seoTitle = $title ?? config('site.seo.default_title');
    $seoDescription = $description ?? config('site.seo.default_description');
    $seoImage = config('site.seo.default_image');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<style>[x-cloak] { display: none !important; }</style>
<body class="font-sans text-ugarte-black antialiased">
    {{-- Navbar --}}
    <x-nav.navbar />

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Floating WhatsApp --}}
    <x-branding.whatsapp-float />

    @stack('scripts')
</body>
</html>
