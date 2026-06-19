@props([
    'links' => [],
])

{{-- Backdrop --}}
<div
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
    @click="close()"
></div>

{{-- Slide-in panel --}}
<div
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    class="fixed inset-y-0 right-0 z-50 w-80 overflow-y-auto bg-white shadow-xl lg:hidden"
    @keydown.escape.window="close()"
>
    <div class="flex items-center justify-between border-b border-gray-100 p-4">
        <a href="/">
            <img src="/images/logo/logo-ugarte-color.png" alt="{{ config('site.name') }}" class="h-8 w-auto">
        </a>
        <button @click="close()" class="text-gray-500 hover:text-ugarte-primary" aria-label="Cerrar menú">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="py-4">
        @foreach($links as $link)
            @if(isset($link['children']))
                {{-- Accordion para sub-items --}}
                <div x-data="{ subOpen: false }">
                    <button
                        @click="subOpen = !subOpen"
                        class="flex w-full items-center justify-between px-4 py-3 text-base font-medium text-ugarte-black hover:bg-ugarte-primary-50 hover:text-ugarte-primary"
                    >
                        {{ $link['label'] }}
                        <svg class="h-4 w-4 transition-transform duration-200" :class="subOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="subOpen" x-collapse class="bg-ugarte-primary-50/50">
                        @foreach($link['children'] as $child)
                            <a
                                href="{{ $child['href'] }}"
                                @click="close()"
                                class="block px-8 py-2.5 text-sm text-ugarte-black/70 transition-colors hover:text-ugarte-primary"
                            >
                                {{ $child['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a
                    href="{{ $link['href'] }}"
                    @click="close()"
                    class="block px-4 py-3 text-base font-medium text-ugarte-black transition-colors hover:bg-ugarte-primary-50 hover:text-ugarte-primary"
                >
                    {{ $link['label'] }}
                </a>
            @endif
        @endforeach
    </nav>

    <div class="space-y-3 border-t border-gray-100 p-4">
        <x-ui.button
            variant="outline"
            size="md"
            href="{{ route('login') }}"
            fullWidth
        >
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
            </svg>
            Aula Virtual
        </x-ui.button>
        <x-ui.button variant="primary" size="md" href="{{ config('site.enrollment_url', '#matriculate') }}" fullWidth>
            Matrículate aquí
        </x-ui.button>
    </div>
</div>
