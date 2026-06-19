@props([])

<x-ui.section id="por-que-ugarte" bg="primary">
    <x-ui.section-heading
        eyebrow="Nuestra trayectoria"
        title="¿Por qué estudiar en Ugarte?"
        subtitle="Años de experiencia nos respaldan formando profesionales exitosos en el sector de la belleza, estética y moda."
        :light="true"
    />

    <x-ui.grid cols="4" gap="8" class="mb-16">
        <x-ui.stats-counter value="500" suffix="+" label="Egresados" />
        <x-ui.stats-counter value="8" label="Programas" />
        <x-ui.stats-counter value="15" suffix="+" label="Años de experiencia" />
        <x-ui.stats-counter value="95" suffix="%" label="Inserción laboral" />
    </x-ui.grid>

    <x-ui.grid cols="3" gap="6">
        <x-ui.feature-card
            title="Certificación MINEDU"
            description="Nuestros programas están certificados por el Ministerio de Educación del Perú."
            iconColor="white"
        />
        <x-ui.feature-card
            title="Infraestructura Moderna"
            description="Instalaciones equipadas con tecnología de última generación para tu formación."
            iconColor="white"
        />
        <x-ui.feature-card
            title="Emprendimiento"
            description="Te preparamos para emprender tu propio negocio en el sector de la belleza, estética y moda."
            iconColor="white"
        />
    </x-ui.grid>
</x-ui.section>
