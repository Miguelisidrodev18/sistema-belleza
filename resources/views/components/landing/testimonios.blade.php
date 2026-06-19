@props([
    'testimonials' => [],
])

<x-ui.section id="testimonios" bg="white">
    <x-ui.section-heading
        eyebrow="Testimonios"
        title="Lo que dicen nuestros egresados"
        subtitle="Historias reales de quienes transformaron su pasión en profesión."
    />

    <x-interactive.carousel :autoplay="true" :interval="6000" :showDots="true" :showArrows="true">
        @foreach($testimonials as $testimonial)
            <div class="w-full shrink-0 px-4">
                <div class="mx-auto max-w-2xl">
                    <x-ui.testimonial-card
                        :name="$testimonial['name']"
                        :role="$testimonial['role']"
                        :quote="$testimonial['quote']"
                        :avatar="$testimonial['avatar'] ?? null"
                        :rating="$testimonial['rating'] ?? null"
                    />
                </div>
            </div>
        @endforeach
    </x-interactive.carousel>
</x-ui.section>
