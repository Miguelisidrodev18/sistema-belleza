@props([
    'title' => null,
])

@php
    $seoTitle = ($title ? $title . config('site.seo.title_suffix') : config('site.seo.default_title'));
    $seoDescription = config('site.seo.default_description');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="font-sans text-ugarte-black antialiased">
    {{-- Future ERP layout: sidebar, topbar, content --}}
    <main>
        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
