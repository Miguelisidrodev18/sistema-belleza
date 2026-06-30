@props([])

@php
    $galleryImages = \App\Models\GalleryImage::orderBy('category')
        ->orderBy('sort_order')
        ->get()
        ->map(fn ($img) => [
            'src'     => $img->url(),
            'alt'     => $img->caption ?? 'Ugarte Escuela Superior',
            'caption' => $img->caption ?? '',
        ])
        ->values()
        ->all();

    // Fallback a placeholders si aún no hay imágenes en BD
    if (empty($galleryImages)) {
        $galleryImages = [
            ['src' => '/images/galeria/placeholder-1.jpg', 'alt' => 'Instalaciones Ugarte',    'caption' => 'Nuestras instalaciones'],
            ['src' => '/images/galeria/placeholder-2.jpg', 'alt' => 'Estudiantes practicando', 'caption' => 'Práctica profesional'],
            ['src' => '/images/galeria/placeholder-3.jpg', 'alt' => 'Evento de graduación',    'caption' => 'Graduación'],
            ['src' => '/images/galeria/placeholder-4.jpg', 'alt' => 'Taller de estilismo',     'caption' => 'Taller de estilismo'],
            ['src' => '/images/galeria/placeholder-5.jpg', 'alt' => 'Clase de maquillaje',     'caption' => 'Clase de maquillaje'],
            ['src' => '/images/galeria/placeholder-6.jpg', 'alt' => 'Equipamiento profesional','caption' => 'Equipamiento'],
        ];
    }
@endphp

<x-ui.section id="galeria" bg="light">
    <x-ui.section-heading
        eyebrow="Galería"
        title="Nuestro mundo"
        subtitle="Conoce nuestras instalaciones, eventos y la experiencia de nuestros estudiantes."
    />

    <div x-data="gallery" data-images='@json($galleryImages)'>
        {{-- Carrusel principal --}}
        <div class="overflow-hidden rounded-2xl">
            <x-interactive.carousel :autoplay="true" :interval="4000" :showDots="true" :showArrows="true">
                @foreach($galleryImages as $index => $image)
                    <div class="relative w-full shrink-0 cursor-pointer" @click="openLightbox({{ $index }})">
                        <div class="aspect-video w-full overflow-hidden bg-gray-900">
                            <x-ui.lazy-image
                                :src="$image['src']"
                                :alt="$image['alt']"
                                aspect="video"
                                rounded="none"
                                class="transition-transform duration-500 hover:scale-105"
                            />
                        </div>
                        @if($image['caption'])
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent p-6">
                                <p class="text-sm font-medium text-white">{{ $image['caption'] }}</p>
                            </div>
                        @endif
                        {{-- Ícono de zoom --}}
                        <div class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-white opacity-0 backdrop-blur-sm transition-opacity hover:bg-white/30 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </div>
                @endforeach
            </x-interactive.carousel>
        </div>

        {{-- Strip de miniaturas --}}
        @if(count($galleryImages) > 1)
            <div class="mt-3 grid gap-1.5"
                 style="grid-template-columns: repeat({{ min(count($galleryImages), 8) }}, 1fr)">
                @foreach(array_slice($galleryImages, 0, 8) as $index => $image)
                    <div class="aspect-square cursor-pointer overflow-hidden rounded-lg opacity-60 ring-0 transition-all hover:opacity-100 hover:ring-2 hover:ring-ugarte-primary"
                         @click="openLightbox({{ $index }})">
                        <x-ui.lazy-image
                            :src="$image['src']"
                            :alt="$image['alt']"
                            aspect="square"
                            rounded="none"
                            class="h-full w-full object-cover"
                        />
                    </div>
                @endforeach
            </div>
        @endif

        <x-overlay.lightbox />
    </div>
</x-ui.section>
