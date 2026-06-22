<x-layouts.app title="Programas">
    <x-slot:actions>
        <a href="{{ route('admin.programs.create') }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
            <x-erp.icon name="plus" class="h-4 w-4" />
            Nuevo Programa
        </a>
    </x-slot:actions>

    @if($currentPeriod)
        <p class="mb-4 text-sm text-gray-500">
            Completitud calculada para el período <strong>{{ $currentPeriod->name }}</strong>.
        </p>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($programs as $program)
            @php
                $c = $program->completeness;
                $pct = $c['percentage'] ?? 0;
            @endphp
            <div class="flex flex-col rounded-xl border border-ugarte-border bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                {{-- Color header --}}
                <div class="h-2" style="background-color: {{ $program->color }}"></div>

                <div class="flex-1 p-5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-semibold text-gray-900 leading-tight">{{ $program->name }}</h3>
                            <p class="mt-0.5 text-xs text-gray-500">{{ $program->duration_months }} meses · {{ $program->total_hours }} h</p>
                        </div>
                        @if(!$program->is_active)
                            <span class="shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-500">Inactivo</span>
                        @endif
                    </div>

                    @if($c !== null)
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gray-500">Configuración</span>
                                <span class="text-xs font-semibold {{ $c['is_complete'] ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $pct }}%
                                </span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-gray-100">
                                <div class="h-1.5 rounded-full transition-all {{ $c['is_complete'] ? 'bg-green-500' : 'bg-yellow-400' }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="mt-1 text-[10px] text-gray-400">
                                {{ $c['with_teacher'] }}/{{ $c['total_courses'] }} cursos con docente asignado
                            </p>
                        </div>
                    @endif
                </div>

                <div class="border-t border-ugarte-border px-5 py-3 flex items-center justify-between">
                    <span class="text-xs text-gray-400">{{ $program->courses_count }} cursos</span>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.programs.show', $program) }}"
                           class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors" title="Ver detalle">
                            <x-erp.icon name="eye" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('admin.programs.edit', $program) }}"
                           class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors" title="Editar">
                            <x-erp.icon name="pencil" class="h-4 w-4" />
                        </a>
                        <form method="POST" action="{{ route('admin.programs.destroy', $program) }}"
                              onsubmit="return confirm('¿Eliminar el programa «{{ $program->name }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Eliminar">
                                <x-erp.icon name="trash" class="h-4 w-4" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-ugarte-border p-12 text-center">
                <x-erp.icon name="academic-cap" class="mx-auto h-10 w-10 text-gray-300" />
                <p class="mt-3 text-sm text-gray-400">No hay programas registrados.</p>
                <a href="{{ route('admin.programs.create') }}" class="mt-2 inline-block text-sm text-ugarte-primary hover:underline">Crear el primero</a>
            </div>
        @endforelse
    </div>
</x-layouts.app>
