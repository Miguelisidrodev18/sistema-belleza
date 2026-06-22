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

    <div x-data="{ tab: 'alumnos' }">
        {{-- Tab bar --}}
        <div class="flex gap-1 border-b border-ugarte-border mb-5">
            <button @click="tab = 'alumnos'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors"
                    :class="tab === 'alumnos' ? 'border-ugarte-primary text-ugarte-primary' : 'border-transparent text-gray-500 hover:text-gray-800'">
                Alumnos
                <span class="ml-1.5 rounded-full bg-gray-100 px-1.5 text-[10px] font-bold text-gray-600">{{ $enrollments->count() }}</span>
            </button>
            <button @click="tab = 'participants'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors"
                    :class="tab === 'participants' ? 'border-ugarte-primary text-ugarte-primary' : 'border-transparent text-gray-500 hover:text-gray-800'">
                Participantes
            </button>
        </div>

        {{-- Tab: Alumnos (tabla original) --}}
        <div x-show="tab === 'alumnos'">
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
        </div>

        {{-- Tab: Participantes --}}
        <div x-show="tab === 'participants'" x-cloak>
            <div class="rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
                <x-lms.participants-list
                    :enrollments="$enrollments"
                    :primaryTeacher="$courseSection->primaryTeacher()"
                    role="docente"
                />
            </div>
        </div>
    </div>
</x-layouts.app>
