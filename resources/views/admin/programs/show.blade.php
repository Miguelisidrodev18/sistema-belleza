<x-layouts.app :title="$program->name">
    <x-slot:actions>
        <a href="{{ route('admin.programs.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Programas
        </a>
        <a href="{{ route('admin.programs.edit', $program) }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
            <x-erp.icon name="pencil" class="h-4 w-4" />
            Editar
        </a>
    </x-slot:actions>

    {{-- Header del programa --}}
    <div class="mb-6 flex items-center gap-4 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
        <div class="h-12 w-12 shrink-0 rounded-xl" style="background-color: {{ $program->color }}"></div>
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-bold text-gray-900">{{ $program->name }}</h2>
            <p class="text-sm text-gray-500">{{ $program->duration_months }} meses · {{ $program->total_hours }} horas</p>
        </div>
        @if($completeness)
            <div class="shrink-0 text-right">
                <p class="text-2xl font-bold {{ $completeness['is_complete'] ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $completeness['percentage'] }}%
                </p>
                <p class="text-xs text-gray-400">configurado</p>
            </div>
        @endif
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: '{{ request('tab', 'courses') }}' }">
        <div class="border-b border-ugarte-border mb-6">
            <nav class="-mb-px flex gap-6">
                <button @click="tab = 'courses'"
                    :class="tab === 'courses' ? 'border-ugarte-primary text-ugarte-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="border-b-2 pb-3 text-sm font-medium transition-colors">
                    Cursos ({{ $program->courses->count() }})
                </button>
                <button @click="tab = 'sections'"
                    :class="tab === 'sections' ? 'border-ugarte-primary text-ugarte-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="border-b-2 pb-3 text-sm font-medium transition-colors">
                    Secciones ({{ $sections->count() }})
                </button>
            </nav>
        </div>

        {{-- Tab: Cursos --}}
        <div x-show="tab === 'courses'">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Cursos del programa</h3>
                <a href="{{ route('admin.programs.courses.create', $program) }}"
                   class="flex items-center gap-1.5 rounded-lg bg-ugarte-primary/10 px-3 py-1.5 text-sm font-medium text-ugarte-primary hover:bg-ugarte-primary/20 transition-colors">
                    <x-erp.icon name="plus" class="h-3.5 w-3.5" />
                    Agregar curso
                </a>
            </div>

            @if($program->courses->isNotEmpty())
                <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-ugarte-border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Curso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Horas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ugarte-border bg-white">
                            @foreach($program->courses as $course)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $course->name }}</td>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $course->code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $course->hours }} h</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $course->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.programs.courses.edit', [$program, $course]) }}"
                                               class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Editar">
                                                <x-erp.icon name="pencil" class="h-4 w-4" />
                                            </a>
                                            <form method="POST" action="{{ route('admin.programs.courses.destroy', [$program, $course]) }}"
                                                  onsubmit="return confirm('¿Eliminar el curso «{{ $course->name }}»?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Eliminar">
                                                    <x-erp.icon name="trash" class="h-4 w-4" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-xl border border-dashed border-ugarte-border p-10 text-center">
                    <p class="text-sm text-gray-400">No hay cursos. <a href="{{ route('admin.programs.courses.create', $program) }}" class="text-ugarte-primary hover:underline">Agregar el primero</a>.</p>
                </div>
            @endif
        </div>

        {{-- Tab: Secciones --}}
        <div x-show="tab === 'sections'">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                    Secciones
                    @if($currentPeriod) — {{ $currentPeriod->name }} @endif
                </h3>
                <a href="{{ route('admin.course-sections.create') }}"
                   class="flex items-center gap-1.5 rounded-lg bg-ugarte-primary/10 px-3 py-1.5 text-sm font-medium text-ugarte-primary hover:bg-ugarte-primary/20 transition-colors">
                    <x-erp.icon name="plus" class="h-3.5 w-3.5" />
                    Nueva sección
                </a>
            </div>

            @if($sections->isNotEmpty())
                <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-ugarte-border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Curso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Sección</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ugarte-border bg-white">
                            @foreach($sections as $section)
                                @php $primaryTeacher = $section->primaryTeacher(); @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $section->course->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">Sección {{ $section->section_code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $primaryTeacher?->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $section->status->value === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $section->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-xl border border-dashed border-ugarte-border p-10 text-center">
                    <p class="text-sm text-gray-400">No hay secciones para este período.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
