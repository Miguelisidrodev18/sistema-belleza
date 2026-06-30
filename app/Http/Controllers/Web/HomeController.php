<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home', [
            'carrerasTecnicas'        => config('programs'),
            'modulosCertificados'     => $this->getModulosCertificados(),
            'cursosPerfeccionamiento' => $this->getCursosPerfeccionamiento(),
            'cursosExtension'         => $this->getCursosExtension(),
            'faqs'                    => $this->getFaqs(),
            'testimonials'            => $this->getTestimonials(),
        ]);
    }

    private function getCursosExtension(): array
    {
        return [
            [
                'name'              => 'Corte de Cabello Básico',
                'slug'              => 'corte-cabello-basico',
                'color'             => '#7B3FA0',
                'icon'              => 'sparkles',
                'short_description' => 'Aprende los fundamentos del corte femenino y masculino para dar tus primeros pasos en el mundo del estilismo.',
                'duration'          => '2 meses',
            ],
            [
                'name'              => 'Maquillaje Social',
                'slug'              => 'maquillaje-social',
                'color'             => '#E91E8C',
                'icon'              => 'sparkles',
                'short_description' => 'Técnicas esenciales de maquillaje de día y noche para lucir perfecta en cualquier evento social.',
                'duration'          => '1 mes',
            ],
            [
                'name'              => 'Manicura y Pedicura',
                'slug'              => 'manicura-pedicura',
                'color'             => '#E8732A',
                'icon'              => 'sparkles',
                'short_description' => 'Tratamientos básicos de manos y pies: esmaltado, cuidado de cutícula y técnicas de uñas naturales.',
                'duration'          => '1 mes',
            ],
            [
                'name'              => 'Diseño de Cejas',
                'slug'              => 'diseno-cejas',
                'color'             => '#1B2A4A',
                'icon'              => 'eye',
                'short_description' => 'Definición, depilación y diseño de cejas para lograr un resultado natural, simétrico y profesional.',
                'duration'          => '3 semanas',
            ],
            [
                'name'              => 'Barbería Básica',
                'slug'              => 'barberia-basica',
                'color'             => '#1D1D1B',
                'icon'              => 'sparkles',
                'short_description' => 'Introducción a la barbería: corte masculino, perfilado de barba y manejo correcto de maquinilla.',
                'duration'          => '1 mes',
            ],
            [
                'name'              => 'Depilación Profesional',
                'slug'              => 'depilacion-profesional',
                'color'             => '#2ABFBF',
                'icon'              => 'sparkles',
                'short_description' => 'Técnicas de depilación con cera, hilo y láminas para brindar un servicio seguro y de calidad.',
                'duration'          => '3 semanas',
            ],
        ];
    }

    private function getModulosCertificados(): array
    {
        return [
            [
                'name'              => 'Colorimetría y Tintes',
                'slug'              => 'colorimetria-tintes',
                'color'             => '#7B3FA0',
                'icon'              => 'sparkles',
                'short_description' => 'Domina la teoría del color, técnicas de coloración, tintes y corrección cromática con certificado oficial.',
                'duration'          => '2 meses',
            ],
            [
                'name'              => 'Extensiones de Pestañas',
                'slug'              => 'extensiones-pestanas',
                'color'             => '#00B4D8',
                'icon'              => 'eye',
                'short_description' => 'Extensión pelo a pelo, volumen ruso y mega volumen para una mirada impactante con certificación.',
                'duration'          => '1 mes',
            ],
            [
                'name'              => 'Micropigmentación de Cejas',
                'slug'              => 'micropigmentacion-cejas',
                'color'             => '#1B2A4A',
                'icon'              => 'pencil',
                'short_description' => 'Microblading y shading para diseño de cejas permanente con resultados naturales y certificado.',
                'duration'          => '6 semanas',
            ],
            [
                'name'              => 'Cosmetología y Skincare',
                'slug'              => 'cosmetologia-skincare',
                'color'             => '#2ABFBF',
                'icon'              => 'sparkles',
                'short_description' => 'Tratamientos faciales, limpieza profunda, peelings y cuidado integral de la piel certificado.',
                'duration'          => '2 meses',
            ],
            [
                'name'              => 'Nail Art Profesional',
                'slug'              => 'nail-art-profesional',
                'color'             => '#E8732A',
                'icon'              => 'sparkles',
                'short_description' => 'Diseños artísticos en uñas: pintura a mano, stamping, foil y técnicas de decoración avanzadas.',
                'duration'          => '1 mes',
            ],
            [
                'name'              => 'Maquillaje para Eventos',
                'slug'              => 'maquillaje-eventos',
                'color'             => '#E91E8C',
                'icon'              => 'sparkles',
                'short_description' => 'Técnicas de maquillaje nupcial, de pasarela y para ocasiones especiales con certificación profesional.',
                'duration'          => '6 semanas',
            ],
        ];
    }

    private function getCursosPerfeccionamiento(): array
    {
        return [
            [
                'name'              => 'Balayage y Técnicas Avanzadas',
                'slug'              => 'balayage-tecnicas-avanzadas',
                'color'             => '#7B3FA0',
                'icon'              => 'sparkles',
                'short_description' => 'Domina balayage, babylights e iluminación libre para ofrecer resultados de alta gama en tu salón.',
                'duration'          => '3 semanas',
            ],
            [
                'name'              => 'Maquillaje Artístico',
                'slug'              => 'maquillaje-artistico',
                'color'             => '#E91E8C',
                'icon'              => 'sparkles',
                'short_description' => 'Efectos especiales, fantasía y caracterización para producciones de moda, teatro y medios audiovisuales.',
                'duration'          => '1 mes',
            ],
            [
                'name'              => 'Barbería Pro: Fade y Diseños',
                'slug'              => 'barberia-pro-fade',
                'color'             => '#1D1D1B',
                'icon'              => 'sparkles',
                'short_description' => 'Fade americano, skin fade, diseños geométricos y barbería artística para un nivel profesional competitivo.',
                'duration'          => '3 semanas',
            ],
            [
                'name'              => 'Gestión de Salón de Belleza',
                'slug'              => 'gestion-salon-belleza',
                'color'             => '#6B7280',
                'icon'              => 'briefcase',
                'short_description' => 'Aprende a administrar tu propio negocio: costos, fidelización de clientes y marketing digital.',
                'duration'          => '1 mes',
            ],
            [
                'name'              => 'Ondas y Alisados Especializados',
                'slug'              => 'ondas-alisados-especializados',
                'color'             => '#2ABFBF',
                'icon'              => 'sparkles',
                'short_description' => 'Permanente, alisado brasileño, keratina y nanoplastia: tratamientos químicos avanzados para profesionales.',
                'duration'          => '3 semanas',
            ],
            [
                'name'              => 'Sistema de Uñas Avanzado',
                'slug'              => 'sistema-unas-avanzado',
                'color'             => '#E8732A',
                'icon'              => 'sparkles',
                'short_description' => 'Acrílico, gel y polygel con técnicas de esculpido, cobertura y diseños de nivel competencia.',
                'duration'          => '3 semanas',
            ],
        ];
    }

    private function getFaqs(): array
    {
        return [
            [
                'question' => '¿Cuáles son los requisitos para inscribirme?',
                'answer' => 'Solo necesitas tener 16 años o más, una copia de tu DNI y muchas ganas de aprender. No se requiere experiencia previa.',
            ],
            [
                'question' => '¿Los certificados son válidos a nivel nacional?',
                'answer' => 'Sí, nuestros certificados están avalados por el Ministerio de Educación del Perú y tienen validez en todo el territorio nacional.',
            ],
            [
                'question' => '¿Cuánto dura cada programa?',
                'answer' => 'La duración varía según el programa. Tenemos cursos de 3, 6 y 12 meses. Consulta la sección de programas para más detalle.',
            ],
            [
                'question' => '¿Ofrecen facilidades de pago?',
                'answer' => 'Sí, contamos con planes de pago flexibles y facilidades para que puedas acceder a la formación que deseas.',
            ],
            [
                'question' => '¿Cuál es el horario de clases?',
                'answer' => 'Ofrecemos horarios en la mañana, tarde y noche para que puedas elegir el que mejor se adapte a tu disponibilidad.',
            ],
        ];
    }

    private function getTestimonials(): array
    {
        return [
            [
                'name' => 'María López',
                'role' => 'Egresada - Estilismo',
                'quote' => 'Gracias a Ugarte pude abrir mi propio salón de belleza. La formación práctica fue clave para emprender con confianza.',
                'avatar' => null,
                'rating' => 5,
            ],
            [
                'name' => 'Carlos Ríos',
                'role' => 'Egresado - Barbería',
                'quote' => 'Los docentes son excelentes profesionales. Aprendí técnicas que me diferenciaron desde el primer día de trabajo.',
                'avatar' => null,
                'rating' => 5,
            ],
            [
                'name' => 'Ana Huamán',
                'role' => 'Egresada - Maquillaje',
                'quote' => 'La mejor decisión que tomé fue estudiar en Ugarte. Hoy trabajo haciendo lo que amo y me siento realizada profesionalmente.',
                'avatar' => null,
                'rating' => 5,
            ],
        ];
    }
}
