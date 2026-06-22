@props([
    'title' => null,
])

@php
    $user = auth()->user();
    $pageTitle = $title ? $title . ' — Aula Virtual Ugarte' : 'Aula Virtual — ' . config('site.name');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans text-gray-900 antialiased bg-[#F8F5FC]">

    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
        {{-- Sidebar --}}
        <x-erp.sidebar :user="$user" />

        {{-- Main content --}}
        <div class="flex flex-1 flex-col min-w-0">
            {{-- Topbar --}}
            <x-erp.topbar :user="$user" />

            {{-- Page content --}}
            <main class="flex-1 p-4 md:p-6 lg:p-8">
                {{-- Breadcrumb --}}
                @hasSection('breadcrumb')
                    <nav class="mb-4 text-sm text-gray-500">
                        @yield('breadcrumb')
                    </nav>
                @endif

                {{-- Page header --}}
                @if($title)
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
                        @isset($actions)
                            <div class="flex items-center gap-3">
                                {{ $actions }}
                            </div>
                        @endisset
                    </div>
                @endif

                {{-- Flash messages --}}
                @if(session('success'))
                    <div x-init="$dispatch('toast', { message: '{{ session('success') }}', type: 'success' })"></div>
                @endif
                @if(session('error'))
                    <div x-init="$dispatch('toast', { message: '{{ session('error') }}', type: 'error' })"></div>
                @endif

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                        <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Toast notifications --}}
    <x-erp.toast />

    @stack('scripts')
</body>
</html>
