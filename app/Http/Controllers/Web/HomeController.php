<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home', [
            'carrerasTecnicas' => config('programs'),
            'modulosCertificados' => [],
            'cursosPerfeccionamiento' => [],
            'cursosExtension' => [],
            'faqs' => $this->getFaqs(),
            'testimonials' => $this->getTestimonials(),
        ]);
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
