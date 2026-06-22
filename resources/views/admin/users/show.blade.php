<x-layouts.app title="Detalle de Usuario">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-ugarte-primary">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Volver a Usuarios
        </a>
    </div>

    <x-slot:actions>
        <x-ui.button variant="outline" size="sm" href="{{ route('admin.users.edit', $user) }}">
            <x-erp.icon name="pencil" class="mr-1.5 h-4 w-4" />
            Editar
        </x-ui.button>
        @if(auth()->id() !== $user->id)
            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">
                @csrf
                @method('PATCH')
                <x-ui.button variant="{{ $user->is_active ? 'danger' : 'primary' }}" size="sm" type="submit">
                    {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                </x-ui.button>
            </form>
        @endif
    </x-slot:actions>

    {{-- User info card --}}
    <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row">
            <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-ugarte-primary/10">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}" class="h-full w-full object-cover" alt="{{ $user->name }}">
                @else
                    <span class="text-2xl font-bold text-ugarte-primary">{{ $user->initials }}</span>
                @endif
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    @php
                        $roleColors = [
                            'administrador' => 'bg-purple-100 text-purple-800',
                            'docente' => 'bg-blue-100 text-blue-800',
                            'alumno' => 'bg-green-100 text-green-800',
                        ];
                    @endphp
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $roleColors[$user->role] }}">
                        {{ $user->role_label }}
                    </span>
                    @if($user->is_active)
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactivo
                        </span>
                    @endif
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <span class="text-gray-500">Email:</span>
                        <span class="ml-1 text-gray-900">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">DNI:</span>
                        <span class="ml-1 text-gray-900">{{ $user->dni ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Teléfono:</span>
                        <span class="ml-1 text-gray-900">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dirección:</span>
                        <span class="ml-1 text-gray-900">{{ $user->address ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Fecha nacimiento:</span>
                        <span class="ml-1 text-gray-900">{{ $user->birth_date?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Género:</span>
                        <span class="ml-1 text-gray-900">
                            {{ match($user->gender) { 'M' => 'Masculino', 'F' => 'Femenino', 'otro' => 'Otro', default => '—' } }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Registrado:</span>
                        <span class="ml-1 text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
