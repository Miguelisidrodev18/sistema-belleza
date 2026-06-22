<x-layouts.app title="Usuarios">
    <x-slot:actions>
        <x-ui.button variant="primary" size="sm" href="{{ route('admin.users.create') }}">
            <x-erp.icon name="plus" class="mr-1.5 h-4 w-4" />
            Nuevo Usuario
        </x-ui.button>
    </x-slot:actions>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end">
        <div class="flex-1">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar por nombre, email o DNI..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary"
            >
        </div>
        <select name="role" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
            <option value="">Todos los roles</option>
            <option value="administrador" {{ request('role') === 'administrador' ? 'selected' : '' }}>Administrador</option>
            <option value="docente" {{ request('role') === 'docente' ? 'selected' : '' }}>Docente</option>
            <option value="alumno" {{ request('role') === 'alumno' ? 'selected' : '' }}>Alumno</option>
        </select>
        <select name="status" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
            <option value="">Todos los estados</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
        </select>
        <x-ui.button variant="outline" size="sm" type="submit">Filtrar</x-ui.button>
        @if(request()->hasAny(['search', 'role', 'status']))
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-ugarte-border bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-600">Usuario</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">DNI</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Rol</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Estado</th>
                        <th class="px-4 py-3 font-semibold text-gray-600 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ugarte-primary/10 text-ugarte-primary text-xs font-semibold">
                                        {{ $user->initials }}
                                    </div>
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.users.show', $user) }}" class="font-medium text-gray-900 hover:text-ugarte-primary">
                                            {{ $user->name }}
                                        </a>
                                        @if($user->must_change_password)
                                            <span class="mt-0.5 w-fit rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-700">
                                                Contraseña temporal
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->dni ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
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
                            </td>
                            <td class="px-4 py-3">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.show', $user) }}" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Ver">
                                        <x-erp.icon name="eye" class="h-4 w-4" />
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Editar">
                                        <x-erp.icon name="pencil" class="h-4 w-4" />
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="{{ $user->is_active ? 'Desactivar' : 'Activar' }}">
                                                @if($user->is_active)
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                                @else
                                                    <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif
</x-layouts.app>
