@props([
    'programs' => [],
])

<x-ui.section id="carreras-tecnicas" bg="white">
    <x-ui.section-heading
        eyebrow="Formación profesional"
        title="Carreras Técnicas"
        subtitle="Programas de formación integral con certificación del Ministerio de Educación para convertirte en un profesional competitivo."
    />

    @if(count($programs) > 0)
        <x-ui.grid cols="4" gap="6">
            @foreach($programs as $program)
                <x-landing.programa-card
                    :name="$program['name']"
                    :slug="$program['slug']"
                    :color="$program['color']"
                    :icon="$program['icon']"
                    :description="$program['short_description']"
                    :duration="$program['duration']"
                />
            @endforeach
        </x-ui.grid>
    @else
        <div class="py-12 text-center">
            <x-ui.icon-box size="lg" color="light" class="mx-auto mb-4">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                </svg>
            </x-ui.icon-box>
            <x-ui.text color="muted">Próximamente se publicarán las carreras técnicas disponibles.</x-ui.text>
        </div>
    @endif
</x-ui.section>
