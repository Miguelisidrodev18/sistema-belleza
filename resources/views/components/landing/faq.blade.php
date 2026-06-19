@props([
    'faqs' => [],
])

<x-ui.section id="preguntas-frecuentes" bg="light">
    <x-ui.section-heading
        eyebrow="FAQ"
        title="Preguntas Frecuentes"
        subtitle="Resolvemos tus dudas más comunes."
    />

    <div class="mx-auto max-w-3xl">
        <x-interactive.accordion :items="$faqs" variant="separated" :defaultOpen="0" />
    </div>
</x-ui.section>
