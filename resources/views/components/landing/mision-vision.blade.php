@props([])

<x-ui.section id="nosotros" bg="white">
    <x-ui.section-heading
        eyebrow="Nuestra esencia"
        title="Conócenos"
        subtitle="Somos una escuela superior comprometida con la formación de profesionales en belleza, estética y moda."
    />

    <x-ui.grid cols="2" gap="8">
        {{-- Misión --}}
        <div id="mision">
            <x-ui.card variant="bordered" padding="lg" :hover="true">
                <div class="text-center">
                    <x-ui.icon-box size="lg" color="primary" variant="filled" class="mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </x-ui.icon-box>

                    <x-ui.heading :level="3" size="md" color="primary">
                        Misión
                    </x-ui.heading>

                    <x-ui.text size="base" color="muted" class="mt-4">
                        Ser una institución formadora de profesionales competitivos, con la visión de convertirse en emprendedores, que contribuyan al desarrollo de la región y del país; atendiendo las demandas en el rubro de la belleza, estética y moda.
                    </x-ui.text>
                </div>
            </x-ui.card>
        </div>

        {{-- Visión --}}
        <div id="vision">
            <x-ui.card variant="bordered" padding="lg" :hover="true">
                <div class="text-center">
                    <x-ui.icon-box size="lg" color="primary" variant="filled" class="mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </x-ui.icon-box>

                    <x-ui.heading :level="3" size="md" color="primary">
                        Visión
                    </x-ui.heading>

                    <x-ui.text size="base" color="muted" class="mt-4">
                        Para el 2030, ser una institución educativa licenciada y reconocida a nivel nacional en el rubro de la belleza, estética y moda.
                    </x-ui.text>
                </div>
            </x-ui.card>
        </div>

        {{-- Valores --}}
        <div id="valores">
            <x-ui.card variant="bordered" padding="lg" :hover="true">
                <div class="text-center">
                    <x-ui.icon-box size="lg" color="primary" variant="filled" class="mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </x-ui.icon-box>

                    <x-ui.heading :level="3" size="md" color="primary">
                        Valores
                    </x-ui.heading>

                    <x-ui.text size="base" color="muted" class="mt-4">
                        Compromiso, excelencia, innovación, responsabilidad social y pasión por la formación integral de nuestros estudiantes.
                    </x-ui.text>
                </div>
            </x-ui.card>
        </div>

        {{-- Propósito --}}
        <div id="proposito">
            <x-ui.card variant="bordered" padding="lg" :hover="true">
                <div class="text-center">
                    <x-ui.icon-box size="lg" color="primary" variant="filled" class="mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </x-ui.icon-box>

                    <x-ui.heading :level="3" size="md" color="primary">
                        Propósito
                    </x-ui.heading>

                    <x-ui.text size="base" color="muted" class="mt-4">
                        Transformar vidas a través de la educación técnica de calidad, empoderando a nuestros egresados para que construyan su propio futuro profesional.
                    </x-ui.text>
                </div>
            </x-ui.card>
        </div>
    </x-ui.grid>
</x-ui.section>
