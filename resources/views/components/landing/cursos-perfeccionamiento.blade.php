@props([
    'cursos' => [],
])

<x-ui.section id="cursos-perfeccionamiento" bg="white">
    <x-ui.section-heading
        eyebrow="Actualización profesional"
        title="Cursos de Perfeccionamiento"
        subtitle="Cursos avanzados para profesionales que buscan actualizar y perfeccionar sus técnicas con las últimas tendencias."
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                </svg>
            </x-ui.icon-box>
            <x-ui.text color="muted">Próximamente se publicarán los cursos de perfeccionamiento disponibles.</x-ui.text>
        </div>
    @endif
</x-ui.section>
