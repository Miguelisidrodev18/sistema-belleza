@props([])

<footer id="footer" class="bg-ugarte-black text-white">
    <div class="section-container py-16">
        <x-ui.grid cols="4" gap="8">
            {{-- Column 1: Logo & About --}}
            <div class="md:col-span-1">
                <x-branding.logo variant="full" size="sm" color="white" />
                <x-ui.text size="sm" color="white" class="mt-4 opacity-70">
                    {{ config('site.description') }}
                </x-ui.text>
                <x-branding.social-links color="white" class="mt-6" />
            </div>

            {{-- Column 2: Quick Links --}}
            <div>
                <x-ui.heading :level="4" size="xs" color="white" class="mb-4">
                    Enlaces rápidos
                </x-ui.heading>
                <nav class="space-y-2">
                    @foreach(config('site.nav_links', []) as $link)
                        <a href="{{ $link['href'] }}" class="block text-sm text-white/70 transition-colors hover:text-white">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Column 3: Programs --}}
            <div>
                <x-ui.heading :level="4" size="xs" color="white" class="mb-4">
                    Programas
                </x-ui.heading>
                <nav class="space-y-2">
                    @foreach(config('programs', []) as $program)
                        <a href="#programas" class="block text-sm text-white/70 transition-colors hover:text-white">
                            {{ $program['name'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Column 4: Contact --}}
            <div>
                <x-ui.heading :level="4" size="xs" color="white" class="mb-4">
                    Contacto
                </x-ui.heading>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-ugarte-secondary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span class="text-sm text-white/70">{{ config('site.address') }}</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-ugarte-secondary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        <a href="tel:{{ config('site.phone') }}" class="text-sm text-white/70 transition-colors hover:text-white">
                            {{ config('site.phone') }}
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-ugarte-secondary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <a href="mailto:{{ config('site.email') }}" class="text-sm text-white/70 transition-colors hover:text-white">
                            {{ config('site.email') }}
                        </a>
                    </div>

                    @if(config('site.whatsapp_schedule'))
                        <p class="mt-2 text-xs text-white/50">
                            {{ config('site.whatsapp_schedule') }}
                        </p>
                    @endif
                </div>
            </div>
        </x-ui.grid>
    </div>

    {{-- Copyright --}}
    <div class="border-t border-white/10">
        <div class="section-container py-6">
            <x-ui.text size="sm" color="white" class="text-center opacity-50">
                &copy; {{ date('Y') }} {{ config('site.name') }}. Todos los derechos reservados.
            </x-ui.text>
        </div>
    </div>
</footer>
