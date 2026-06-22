<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aula Virtual — {{ config('site.name') }}</title>

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans text-[#1D1D1B] antialiased bg-[#F8F5FC]">

    <div class="flex min-h-screen" x-data="{ selectedRole: 'alumno' }">

        {{-- PANEL IZQUIERDO: Cine de Aprendizaje & Beneficios --}}
        <div class="relative hidden w-[42%] md:flex lg:w-[45%] flex-col justify-between p-12 lg:p-16 text-white overflow-hidden select-none">
            {{-- Foto real de aprendizaje con ligero blur e iluminación --}}
            <img
                src="/images/teacher_students_login.png"
                alt="Docentes y estudiantes en Ugarte"
                class="absolute inset-0 h-full w-full object-cover filter blur-[0.5px] scale-[1.01] pointer-events-none"
            >
            
            {{-- Overlay morado elegante con degradado radial y lineal --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#200F30]/98 via-[#532B7C]/82 to-[#1D1D1B]/35"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(83,43,124,0.45),transparent_70%)]"></div>

            {{-- Formas decorativas sutiles --}}
            <div class="absolute top-[20%] right-[-10%] w-[350px] h-[350px] bg-white/[0.02] rounded-full filter blur-[100px] pointer-events-none"></div>
            <div class="absolute bottom-[10%] left-[-5%] w-[250px] h-[250px] bg-[#653F87]/[0.15] rounded-full filter blur-[80px] pointer-events-none"></div>

            {{-- Volver a la landing (Superior) --}}
            <div class="relative z-10 animate-fade-in">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-white/80 hover:text-white transition-colors duration-250 font-medium group">
                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Volver al sitio web
                </a>
            </div>

            {{-- Contenido (Inferior): Beneficios + Stats --}}
            <div class="relative z-10 flex flex-col gap-8 mt-auto animate-fade-in-up">
                {{-- Textos principales --}}
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-white/10 border border-white/20 backdrop-blur-md text-white/95 uppercase tracking-wide">
                        Escuela Superior
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-bold tracking-tight text-white leading-tight text-balance font-sans">
                        Construyendo el futuro de la estética profesional
                    </h2>
                    <p class="text-white/85 text-sm lg:text-base leading-relaxed max-w-md font-light">
                        Capacitación técnica de alto nivel con un enfoque 100% práctico para insertarte con éxito en el mercado de la belleza.
                    </p>
                </div>
                
                {{-- Lista de beneficios --}}
                <div class="space-y-4 border-t border-white/15 pt-6">
                    {{-- Certificación --}}
                    <div class="flex items-start gap-3.5 group">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 border border-white/20 transition-all duration-300 group-hover:bg-white/15">
                            <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-sm font-semibold text-white">Certificación Oficial</h3>
                            <p class="text-xs text-white/70">Títulos oficiales avalados por el Ministerio de Educación.</p>
                        </div>
                    </div>
                    
                    {{-- Docentes especializados --}}
                    <div class="flex items-start gap-3.5 group">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 border border-white/20 transition-all duration-300 group-hover:bg-white/15">
                            <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-sm font-semibold text-white">Docentes Especializados</h3>
                            <p class="text-xs text-white/70">Aprende directamente de estilistas y técnicos en activo.</p>
                        </div>
                    </div>

                    {{-- Bolsa laboral --}}
                    <div class="flex items-start gap-3.5 group">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 border border-white/20 transition-all duration-300 group-hover:bg-white/15">
                            <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-sm font-semibold text-white">Bolsa de Empleo Activa</h3>
                            <p class="text-xs text-white/70">Alianzas comerciales directas para acelerar tu inserción laboral.</p>
                        </div>
                    </div>

                    {{-- Horarios flexibles --}}
                    <div class="flex items-start gap-3.5 group">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 border border-white/20 transition-all duration-300 group-hover:bg-white/15">
                            <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-sm font-semibold text-white">Flexibilidad Horaria</h3>
                            <p class="text-xs text-white/70">Grupos por mañana, tarde y noche adaptados a tu ritmo.</p>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta de estadísticas corporativa (limpia y sutil) --}}
                <div class="bg-white/[0.04] border border-white/10 rounded-xl p-5 backdrop-blur-[3px] shadow-[0_8px_32px_rgba(0,0,0,0.15)] mt-2">
                    <div class="grid grid-cols-3 gap-2 text-center divide-x divide-white/10">
                        <div>
                            <p class="text-2xl font-bold text-white tracking-tight font-sans">+15 000</p>
                            <p class="text-[9px] text-white/60 uppercase font-semibold tracking-wider mt-1">Estudiantes</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white tracking-tight font-sans">25 años</p>
                            <p class="text-[9px] text-white/60 uppercase font-semibold tracking-wider mt-1">Trayectoria</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white tracking-tight font-sans">9</p>
                            <p class="text-[9px] text-white/60 uppercase font-semibold tracking-wider mt-1">Programas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL DERECHO: Formulario Premium --}}
        <div class="flex w-full flex-col justify-center items-center px-6 py-12 md:w-[58%] lg:w-[55%]">

            {{-- Logo mobile (visible en mobile) --}}
            <div class="mb-8 flex justify-center md:hidden animate-fade-in">
                <a href="{{ route('home') }}">
                    <img
                        src="/images/logo/logo-ugarte-color.png"
                        alt="{{ config('site.name') }}"
                        class="h-10 w-auto object-contain"
                    >
                </a>
            </div>

            <div class="w-full max-w-md animate-fade-in-up">
                
                {{-- Logo desktop --}}
                <div class="mb-10 hidden md:block text-center">
                    <a href="{{ route('home') }}" class="inline-block transition-transform duration-200 hover:scale-[1.01]">
                        <img
                            src="/images/logo/logo-ugarte-color.png"
                            alt="{{ config('site.name') }}"
                            class="h-11 w-auto object-contain mx-auto"
                        >
                    </a>
                </div>

                {{-- Contenedor del Formulario flotante (rounded 24px, sombras suaves, espacioso) --}}
                <div class="bg-white border border-[#E8DDF0]/50 rounded-[24px] p-8 md:p-10 shadow-[0_16px_48px_rgba(83,43,124,0.03),0_1px_3px_rgba(83,43,124,0.01)]">
                    
                    {{-- Título y subtítulo --}}
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-semibold tracking-tight text-[#1D1D1B] font-sans">
                            Aula Virtual
                        </h1>
                        <p class="mt-1.5 text-sm text-[#1D1D1B]/55 font-light leading-relaxed">
                            Selecciona tu perfil e ingresa tus credenciales
                        </p>
                    </div>

                    {{-- Selector de roles (Segmented control estilo Linear) --}}
                    <div class="p-1 bg-[#F8F5FC] rounded-xl grid grid-cols-3 gap-1 border border-[#E8DDF0]/35 mb-8 select-none">
                        {{-- Administrador --}}
                        <button
                            type="button"
                            @click="selectedRole = 'administrador'"
                            :class="selectedRole === 'administrador'
                                ? 'bg-white text-[#532B7C] shadow-[0_2px_8px_rgba(83,43,124,0.05)] border-[#E8DDF0]/60 font-medium'
                                : 'text-gray-500 hover:text-gray-900 border-transparent'"
                            class="class-role-btn flex flex-col items-center justify-center gap-1.5 py-3 rounded-lg border text-xs cursor-pointer"
                        >
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Admin</span>
                        </button>

                        {{-- Docente --}}
                        <button
                            type="button"
                            @click="selectedRole = 'docente'"
                            :class="selectedRole === 'docente'
                                ? 'bg-white text-[#532B7C] shadow-[0_2px_8px_rgba(83,43,124,0.05)] border-[#E8DDF0]/60 font-medium'
                                : 'text-gray-500 hover:text-gray-900 border-transparent'"
                            class="class-role-btn flex flex-col items-center justify-center gap-1.5 py-3 rounded-lg border text-xs cursor-pointer"
                        >
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                            </svg>
                            <span>Docente</span>
                        </button>

                        {{-- Alumno --}}
                        <button
                            type="button"
                            @click="selectedRole = 'alumno'"
                            :class="selectedRole === 'alumno'
                                ? 'bg-white text-[#532B7C] shadow-[0_2px_8px_rgba(83,43,124,0.05)] border-[#E8DDF0]/60 font-medium'
                                : 'text-gray-500 hover:text-gray-900 border-transparent'"
                            class="class-role-btn flex flex-col items-center justify-center gap-1.5 py-3 rounded-lg border text-xs cursor-pointer"
                        >
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span>Alumno</span>
                        </button>
                    </div>

                    {{-- Errores de validación --}}
                    @if($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3">
                            <ul class="list-disc space-y-1 pl-4 text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Formulario de login --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-6 class-login-form">
                        @csrf
                        <input type="hidden" name="role" :value="selectedRole">

                        <x-form.input
                            name="email"
                            label="Correo electrónico o usuario"
                            type="email"
                            placeholder="tu@correo.com"
                            :required="true"
                        />

                        <x-form.input
                            name="password"
                            label="Contraseña"
                            type="password"
                            placeholder="••••••••"
                            :required="true"
                        />

                        <div class="flex items-center justify-between pt-1">
                            <x-form.checkbox
                                name="remember"
                                label="Recordarme"
                            />
                            <a href="#" class="text-sm font-medium text-[#532B7C] hover:text-[#653F87] transition-colors duration-200">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>

                        <x-ui.button
                            variant="primary"
                            size="md"
                            type="submit"
                            fullWidth
                            class="mt-6 class-btn-submit"
                        >
                            Ingresar
                        </x-ui.button>
                    </form>
                </div>

                {{-- Copyright Footer --}}
                <p class="mt-12 text-center text-xs text-gray-400 font-light tracking-wide">
                    &copy; {{ date('Y') }} {{ config('site.name') }}. Todos los derechos reservados.
                </p>

                {{-- Volver (mobile) --}}
                <div class="mt-6 text-center md:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-[#532B7C] hover:text-[#653F87] transition-colors duration-250 font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
