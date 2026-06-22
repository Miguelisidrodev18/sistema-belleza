<x-layouts.app title="Secciones Académicas">
    <x-slot:actions>
        <a href="{{ route('admin.course-sections.create') }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
            <x-erp.icon name="plus" class="h-4 w-4" />
            Nueva Sección
        </a>
    </x-slot:actions>

    {{-- Filtro por período --}}
    <form method="GET" class="mb-4 flex items-center gap-3">
        <select name="period_id"
            class="rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
            <option value="">Todos los períodos</option>
            @foreach($periods as $period)
                <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                    {{ $period->name }}{{ $period->is_current ? ' (actual)' : '' }}
                </option>
            @endforeach
        </select>
        <button type="submit"
            class="rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            Filtrar
        </button>
        @if($selectedPeriodId)
            <a href="{{ route('admin.course-sections.index') }}" class="text-sm text-gray-400 hover:text-gray-600">Limpiar</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-ugarte-border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Curso</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Sección</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Período</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Docente</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Cupos</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ugarte-border bg-white">
                @forelse($sections as $section)
                    @php $primaryTeacher = $section->primaryTeacher(); @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $section->course->name }}</p>
                                <p class="text-xs text-gray-400">{{ $section->course->program->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-700">{{ $section->section_code }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $section->academicPeriod->name }}
                            @if($section->academicPeriod->is_current)
                                <span class="ml-1 text-[10px] font-bold text-ugarte-primary">actual</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $primaryTeacher?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $section->available_slots }} / {{ $section->capacity }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($section->status->value) {
                                    'published' => 'bg-green-100 text-green-700',
                                    'draft'     => 'bg-gray-100 text-gray-500',
                                    'closed'    => 'bg-yellow-100 text-yellow-700',
                                    'finished'  => 'bg-blue-100 text-blue-700',
                                    'archived'  => 'bg-red-100 text-red-600',
                                    default     => 'bg-gray-100 text-gray-500',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColor }}">
                                {{ $section->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.course-sections.show', $section) }}"
                                   class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Ver detalle">
                                    <x-erp.icon name="eye" class="h-4 w-4" />
                                </a>
                                <a href="{{ route('admin.course-sections.edit', $section) }}"
                                   class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Editar">
                                    <x-erp.icon name="pencil" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.course-sections.destroy', $section) }}"
                                      onsubmit="return confirm('¿Eliminar esta sección?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Eliminar">
                                        <x-erp.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400">
                            No hay secciones para este filtro.
                            <a href="{{ route('admin.course-sections.create') }}" class="text-ugarte-primary hover:underline">Crear la primera</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sections->hasPages())
        <div class="mt-4">{{ $sections->links() }}</div>
    @endif
</x-layouts.app>
