<x-layouts.app title="Horarios — {{ $courseSection->course->name }}">
    <x-slot:actions>
        <a href="{{ route('admin.course-sections.schedules.create', $courseSection) }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-dark transition-colors">
            <x-erp.icon name="plus" class="h-4 w-4" />
            Nuevo Horario
        </a>
    </x-slot:actions>

    <div class="mb-4 rounded-xl border border-ugarte-border bg-white p-4 shadow-sm">
        <p class="text-sm text-gray-500">Sección: <span class="font-medium text-gray-800">{{ $courseSection->course->name }} — Sección {{ $courseSection->section_code }}</span></p>
        <p class="text-sm text-gray-500 mt-1">Período: <span class="font-medium text-gray-800">{{ $courseSection->academicPeriod->name }}</span></p>
    </div>

    <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-ugarte-border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Día</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Hora inicio</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Hora fin</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Aula</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Modalidad</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ugarte-border">
                @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $schedule->day_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $schedule->start_time }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $schedule->end_time }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->room ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $schedule->modality->badgeClass() }}">
                                {{ $schedule->modality->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($schedule->is_active)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Activo</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 flex gap-2 justify-end">
                            <a href="{{ route('admin.course-sections.schedules.edit', [$courseSection, $schedule]) }}"
                               class="text-xs text-ugarte-primary hover:underline">Editar</a>
                            <form action="{{ route('admin.course-sections.schedules.destroy', [$courseSection, $schedule]) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este horario?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-400">
                            No hay horarios. <a href="{{ route('admin.course-sections.schedules.create', $courseSection) }}" class="text-ugarte-primary hover:underline">Crear el primero</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
