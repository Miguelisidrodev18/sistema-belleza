@props([
    'cursos' => [],
])

<x-ui.section id="cursos-extension" bg="light">
    <x-ui.section-heading
        eyebrow="Formación abierta"
        title="Cursos de Extensión"
        subtitle="Cursos abiertos al público para quienes desean iniciarse en el mundo de la belleza, estética y moda sin requisitos previos."
    />

    @if(count($cursos) > 0)
        <x-ui.grid cols="3" gap="6">
            @foreach($cursos as $curso)
                <x-landing.programa-card
                    :name="$curso['name']"
                    :slug="$curso['slug']"
                    :color="$curso['color']"
                    :icon="$curso['icon']"
                    :description="$curso['short_description']"
                    :duration="$curso['duration']"
                />
            @endforeach
        </x-ui.grid>
    @else
        <div class="py-12 text-center">
            <x-ui.icon-box size="lg" color="light" class="mx-auto mb-4">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </x-ui.icon-box>
            <x-ui.text color="muted">Próximamente se publicarán los cursos de extensión disponibles.</x-ui.text>
        </div>
    @endif
</x-ui.section>
