<x-layouts.app title="Alumnos de Sección">
    <x-slot:actions>
        <a href="{{ route('docente.sections.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Mis Secciones
        </a>
    </x-slot:actions>

    <div class="mb-4 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
            <h2 class="text-lg font-semibold text-gray-900">{{ $courseSection->course->name }}</h2>
            <span class="text-sm text-gray-400">|</span>
            <span class="text-sm text-gray-600">{{ $courseSection->course->program->name }}</span>
            <span class="text-sm text-gray-400">|</span>
            <span class="text-sm text-gray-600">Sección {{ $courseSection->section_code }}</span>
            <span class="text-sm text-gray-400">|</span>
            <span class="text-sm text-gray-600">{{ $courseSection->academicPeriod->name }}</span>
            <span class="ml-auto text-sm text-gray-500">
                {{ $enrollments->filter(fn($e) => $e->status->value === 'activa')->count() }} activos / {{ $courseSection->capacity }} cupos
            </span>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-ugarte-border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">N° Matrícula</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Alumno</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Matriculado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ugarte-border">
                @forelse($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-sm font-semibold text-ugarte-primary">
                            {{ $enrollment->enrollment_number }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $enrollment->alumno->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $enrollment->alumno->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $enrollment->status->badgeClass() }}">
                                {{ $enrollment->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $enrollment->enrolled_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-400">
                            No hay alumnos matriculados en esta sección.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
