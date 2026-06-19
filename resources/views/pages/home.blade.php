<x-layouts.landing>
    <x-landing.hero />
    <x-landing.quienes-somos />
    <x-landing.mision-vision />
    <x-landing.por-que-ugarte />
    <x-landing.carreras-tecnicas :programs="$carrerasTecnicas" />
    <x-landing.modulos-certificados :modulos="$modulosCertificados" />
    <x-landing.cursos-perfeccionamiento :cursos="$cursosPerfeccionamiento" />
    <x-landing.cursos-extension :cursos="$cursosExtension" />
    <x-landing.beneficios />
    <x-landing.galeria />
    <x-landing.testimonios :testimonials="$testimonials" />
    <x-landing.faq :faqs="$faqs" />
    <x-landing.matriculate />
</x-layouts.landing>
