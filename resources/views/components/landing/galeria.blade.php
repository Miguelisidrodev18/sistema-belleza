@props([])

@php
    $galleryImages = [
        ['src' => '/images/galeria/placeholder-1.jpg', 'alt' => 'Instalaciones Ugarte', 'caption' => 'Nuestras instalaciones'],
        ['src' => '/images/galeria/placeholder-2.jpg', 'alt' => 'Estudiantes practicando', 'caption' => 'Práctica profesional'],
        ['src' => '/images/galeria/placeholder-3.jpg', 'alt' => 'Evento de graduación', 'caption' => 'Graduación'],
        ['src' => '/images/galeria/placeholder-4.jpg', 'alt' => 'Taller de estilismo', 'caption' => 'Taller de estilismo'],
        ['src' => '/images/galeria/placeholder-5.jpg', 'alt' => 'Clase de maquillaje', 'caption' => 'Clase de maquillaje'],
        ['src' => '/images/galeria/placeholder-6.jpg', 'alt' => 'Equipamiento profesional', 'caption' => 'Equipamiento'],
    ];
@endphp

<x-ui.section id="galeria" bg="light">
    <x-ui.section-heading
        eyebrow="Galería"
        title="Nuestro mundo"
        subtitle="Conoce nuestras instalaciones, eventos y la experiencia de nuestros estudiantes."
    />

    <div x-data="gallery" data-images='@json($galleryImages)'>
        <x-ui.grid cols="3" gap="4">
            @foreach($galleryImages as $index => $image)
                <x-ui.gallery-item
                    :src="$image['src']"
                    :alt="$image['alt']"
                    :caption="$image['caption']"
                    :index="$index"
                />
            @endforeach
        </x-ui.grid>

        <x-overlay.lightbox />
    </div>
</x-ui.section>
