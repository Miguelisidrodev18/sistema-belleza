@props([])

<x-ui.section id="quienes-somos" bg="light">
    <x-ui.grid cols="2" gap="12">
        <div class="flex flex-col justify-center">
            <x-ui.section-heading
                eyebrow="Sobre nosotros"
                title="¿Quiénes somos?"
                align="left"
                :decorated="true"
            />

            <x-ui.text size="lg" color="muted" class="mt-2">
                Somos una escuela superior dedicada a la formación de profesionales competitivos en el rubro de la belleza, estética y moda. Con años de experiencia formando talento en Huancayo.
            </x-ui.text>

            <div class="mt-6 space-y-3">
                <x-ui.feature-item text="Aprendizaje 100% práctico desde el primer día" />
                <x-ui.feature-item text="Docentes especializados con experiencia en el sector" />
                <x-ui.feature-item text="Certificación avalada por el Ministerio de Educación" />
            </div>
        </div>

        <div class="flex items-center justify-center">
            {{-- Placeholder for institutional image --}}
            <div class="aspect-[4/3] w-full rounded-2xl bg-ugarte-primary-100"></div>
        </div>
    </x-ui.grid>
</x-ui.section>
