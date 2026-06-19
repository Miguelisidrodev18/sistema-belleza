@props([
    'modulos' => [],
])

<x-ui.section id="modulos-certificados" bg="light">
    <x-ui.section-heading
        eyebrow="Especialización"
        title="Módulos Certificados"
        subtitle="Módulos especializados con certificación oficial para potenciar tus habilidades en áreas específicas de la belleza y estética."
    />

    @if(count($modulos) > 0)
        <x-ui.grid cols="3" gap="6">
            @foreach($modulos as $modulo)
                <x-landing.programa-card
                    :name="$modulo['name']"
                    :slug="$modulo['slug']"
                    :color="$modulo['color']"
                    :icon="$modulo['icon']"
                    :description="$modulo['short_description']"
                    :duration="$modulo['duration']"
                />
            @endforeach
        </x-ui.grid>
    @else
        <div class="py-12 text-center">
            <x-ui.icon-box size="lg" color="light" class="mx-auto mb-4">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </x-ui.icon-box>
            <x-ui.text color="muted">Próximamente se publicarán los módulos certificados disponibles.</x-ui.text>
        </div>
    @endif
</x-ui.section>
