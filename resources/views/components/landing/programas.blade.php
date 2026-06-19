@props([
    'programs' => [],
])

<x-ui.section id="programas" bg="white">
    <x-ui.section-heading
        eyebrow="Nuestra oferta educativa"
        title="Nuestros Programas"
        subtitle="Elige el programa que más se adapte a tu pasión y conviértete en un profesional de la belleza."
    />

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
</x-ui.section>
