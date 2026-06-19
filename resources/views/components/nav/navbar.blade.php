@props([
    'transparent' => true,
    'fixed' => true,
    'links' => null,
])

@php
    $navLinks = $links ?? config('site.nav_links', []);
@endphp

<header
    x-data="navbar"
    {{ $attributes->merge(['class' => 'w-full z-40 transition-all duration-300 ' . ($fixed ? 'fixed top-0 left-0 right-0' : 'relative')]) }}
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-navbar' : '{{ $transparent ? 'bg-transparent' : 'bg-white shadow-navbar' }}'"
>
    <x-ui.container>
        <nav class="flex h-16 items-center justify-between md:h-20" aria-label="Navegación principal">
            {{-- Logo: blanco arriba, color al scroll --}}
            <a href="/" class="shrink-0">
                <img
                    x-show="!scrolled"
                    src="/images/logo/logo-ugarte-blanco.png"
                    alt="{{ config('site.name') }}"
                    class="h-10 w-auto object-contain md:h-12"
                >
                <img
                    x-show="scrolled"
                    x-cloak
                    src="/images/logo/logo-ugarte-color.png"
                    alt="{{ config('site.name') }}"
                    class="h-10 w-auto object-contain md:h-12"
                >
            </a>

            {{-- Desktop nav links --}}
            <div class="hidden items-center gap-6 lg:flex">
                @foreach($navLinks as $link)
                    @if(isset($link['children']))
                        {{-- Dropdown --}}
                        <div class="relative" x-data="{ dropdownOpen: false }" @mouseenter="dropdownOpen = true" @mouseleave="dropdownOpen = false">
                            <button
                                class="inline-flex items-center gap-1 text-sm font-medium transition-colors duration-300"
                                :class="scrolled ? 'text-ugarte-black/70 hover:text-ugarte-primary' : '{{ $transparent ? 'text-white/80 hover:text-white' : 'text-ugarte-black/70 hover:text-ugarte-primary' }}'"
                            >
                                {{ $link['label'] }}
                                <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="dropdownOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                x-cloak
                                class="absolute left-0 top-full mt-2 w-48 rounded-xl bg-white py-2 shadow-card-hover ring-1 ring-black/5"
                            >
                                @foreach($link['children'] as $child)
                                    <a
                                        href="{{ $child['href'] }}"
                                        class="block px-4 py-2 text-sm text-ugarte-black/70 transition-colors hover:bg-ugarte-primary-50 hover:text-ugarte-primary"
                                    >
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Link simple --}}
                        <a
                            href="{{ $link['href'] }}"
                            class="text-sm font-medium transition-colors duration-300"
                            :class="scrolled ? 'text-ugarte-black/70 hover:text-ugarte-primary' : '{{ $transparent ? 'text-white/80 hover:text-white' : 'text-ugarte-black/70 hover:text-ugarte-primary' }}'"
                        >
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Desktop CTAs --}}
            <div class="hidden items-center gap-3 lg:flex">
                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 rounded-lg border-2 px-4 py-2 text-sm font-semibold transition-all duration-300"
                    :class="scrolled
                        ? 'border-ugarte-primary text-ugarte-primary hover:bg-ugarte-primary hover:text-white'
                        : '{{ $transparent ? "border-white/70 text-white hover:bg-white/10" : "border-ugarte-primary text-ugarte-primary hover:bg-ugarte-primary hover:text-white" }}'"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                    Aula Virtual
                </a>
                <x-ui.button
                    variant="primary"
                    size="sm"
                    href="{{ config('site.enrollment_url', '#matriculate') }}"
                >
                    Matrículate
                </x-ui.button>
            </div>

            {{-- Mobile hamburger --}}
            <button
                @click="toggle()"
                class="flex items-center lg:hidden"
                :class="scrolled ? 'text-ugarte-black' : '{{ $transparent ? 'text-white' : 'text-ugarte-black' }}'"
                aria-label="Abrir menú"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </nav>
    </x-ui.container>

    {{-- Mobile menu --}}
    <x-nav.mobile-menu :links="$navLinks" />
</header>
