@props(['user'])

@php
    $role = $user->role;

    $adminNav = [
        ['label' => 'Dashboard',           'route' => 'admin.dashboard',              'icon' => 'home'],
        ['section' => 'Gestionar'],
        ['label' => 'Usuarios',            'route' => 'admin.users.index',             'icon' => 'users',         'prefix' => 'admin.users'],
        ['label' => 'Programas',           'route' => 'admin.programs.index',          'icon' => 'academic-cap',  'prefix' => 'admin.programs'],
        ['label' => 'Períodos Académicos', 'route' => 'admin.academic-periods.index',  'icon' => 'calendar-days', 'prefix' => 'admin.academic-periods'],
        ['section' => 'Académico'],
        ['label' => 'Secciones',           'route' => 'admin.course-sections.index',   'icon' => 'clipboard-list','prefix' => 'admin.course-sections'],
        ['label' => 'Matrículas',          'route' => 'admin.enrollments.index',        'icon' => 'user-group',   'prefix' => 'admin.enrollments'],
        ['label' => 'Re-matrícula',        'route' => 'admin.re-enrollment.index',      'icon' => 'folder-open',  'prefix' => 'admin.re-enrollment'],
        ['section' => 'Programación'],
        ['label' => 'Sesiones',            'route' => 'admin.class-sessions.index',     'icon' => 'clock',        'prefix' => 'admin.class-sessions'],
        ['label' => 'Generar Sesiones',    'route' => 'admin.session-generator.index',  'icon' => 'calendar-days','prefix' => 'admin.session-generator'],
    ];

    $docenteNav = [
        ['label' => 'Dashboard',    'route' => 'docente.dashboard',        'icon' => 'home'],
        ['section' => ''],
        ['label' => 'Mis Secciones','route' => 'docente.sections.index',        'icon' => 'clipboard-list', 'prefix' => 'docente.sections'],
        ['label' => 'Mis Clases',   'route' => 'docente.class-sessions.index',  'icon' => 'clock',          'prefix' => 'docente.class-sessions'],
        ['label' => 'Mi Perfil',    'route' => 'docente.profile.edit',          'icon' => 'user-circle',    'prefix' => 'docente.profile'],
    ];

    $alumnoNav = [
        ['label' => 'Dashboard',       'route' => 'alumno.dashboard',          'icon' => 'home'],
        ['section' => ''],
        ['label' => 'Mis Matrículas',  'route' => 'alumno.enrollments.index',  'icon' => 'academic-cap',  'prefix' => 'alumno.enrollments'],
        ['label' => 'Calendario',      'route' => 'alumno.calendar.index',      'icon' => 'calendar-days', 'prefix' => 'alumno.calendar'],
        ['label' => 'Mi Perfil',       'route' => 'alumno.profile.edit',        'icon' => 'user-circle',   'prefix' => 'alumno.profile'],
    ];

    $navItems = match ($role) {
        'administrador' => $adminNav,
        'docente' => $docenteNav,
        'alumno' => $alumnoNav,
    };
@endphp

{{-- Overlay mobile --}}
<div
    x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-black/50 md:hidden"
    @click="sidebarOpen = false"
    x-cloak
></div>

{{-- Sidebar --}}
<aside
    :class="sidebarOpen ? 'translate-x-0' : 'max-md:-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-white border-r border-ugarte-border shadow-sm transition-transform duration-300 ease-in-out md:static md:z-auto md:translate-x-0"
>
    {{-- User info --}}
    <div class="flex items-center gap-3 border-b border-ugarte-border px-5 py-5">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-ugarte-primary text-white text-sm font-semibold">
            {{ $user->initials }}
        </div>
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-gray-900">{{ $user->name }}</p>
            <p class="text-xs text-gray-500">{{ $user->role_label }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        @foreach($navItems as $item)
            @if(isset($item['section']))
                @if($item['section'] !== '')
                    <p class="mt-5 mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        {{ $item['section'] }}
                    </p>
                @else
                    <hr class="my-3 border-ugarte-border">
                @endif
            @else
                @php
                    $isActive = isset($item['prefix'])
                        ? request()->routeIs($item['prefix'] . '*')
                        : request()->routeIs($item['route']);
                @endphp
                <a
                    href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors duration-150
                        {{ $isActive
                            ? 'bg-ugarte-primary/10 text-ugarte-primary'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    <x-erp.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="border-t border-ugarte-border px-3 py-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-150">
                <x-erp.icon name="arrow-right-start" class="h-5 w-5 shrink-0" />
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>
