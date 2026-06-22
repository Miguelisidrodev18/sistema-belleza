@props(['user'])

<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-ugarte-border bg-white px-4 shadow-sm md:px-6">
    {{-- Left: Hamburger + Logo --}}
    <div class="flex items-center gap-3">
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 md:hidden"
        >
            <x-erp.icon name="bars-3" class="h-5 w-5" />
        </button>

        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="/images/logo/logo-ugarte-color.png" alt="Ugarte" class="h-8 w-auto">
        </a>
    </div>

    {{-- Right: User menu --}}
    <div class="flex items-center gap-3">
        {{-- User dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button
                @click="open = !open"
                class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-gray-50 transition-colors"
            >
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-ugarte-primary text-white text-xs font-semibold">
                    {{ $user->initials }}
                </div>
                <span class="hidden text-sm font-medium text-gray-700 sm:block">{{ $user->name }}</span>
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 rounded-lg bg-white py-1 shadow-lg ring-1 ring-black/5"
                x-cloak
            >
                @if($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                @elseif($user->isDocente())
                    <a href="{{ route('docente.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Mi Perfil</a>
                @else
                    <a href="{{ route('alumno.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Mi Perfil</a>
                @endif
                <hr class="my-1 border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
