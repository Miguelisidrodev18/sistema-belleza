<x-layouts.app :title="$courseSection->section_code . ' — ' . $courseSection->course->name">
    <x-slot:actions>
        <a href="{{ route('admin.course-sections.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Secciones
        </a>
        <a href="{{ route('admin.course-sections.edit', $courseSection) }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-dark transition-colors">
            <x-erp.icon name="pencil" class="h-4 w-4" />
            Editar
        </a>
    </x-slot:actions>

    {{-- Page title --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-900">{{ $courseSection->section_code }}</h1>
        <p class="text-sm text-gray-500">{{ $courseSection->course->name }} · {{ $courseSection->academicPeriod->name }}</p>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: '{{ $activeTab }}' }">

        {{-- Tab bar --}}
        <div class="flex gap-1 border-b border-ugarte-border mb-5">
            @foreach([
                ['id' => 'general',   'label' => 'General'],
                ['id' => 'horarios',  'label' => 'Horarios'],
                ['id' => 'materials', 'label' => 'Materiales'],
                ['id' => 'alumnos',   'label' => 'Alumnos'],
            ] as $t)
            <button @click="tab = '{{ $t['id'] }}'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors"
                    :class="tab === '{{ $t['id'] }}' ? 'border-ugarte-primary text-ugarte-primary' : 'border-transparent text-gray-500 hover:text-gray-800'">
                {{ $t['label'] }}
                @if($t['id'] === 'materials')
                <span class="ml-1.5 rounded-full bg-ugarte-primary/10 px-1.5 text-[10px] font-bold text-ugarte-primary">{{ $materials->count() }}</span>
                @endif
                @if($t['id'] === 'alumnos')
                <span class="ml-1.5 rounded-full bg-gray-100 px-1.5 text-[10px] font-semibold text-gray-500">{{ $enrollments->count() }}</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- TAB: General --}}
        <div x-show="tab === 'general'" x-cloak>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $primaryTeacher = $courseSection->teachers->where('pivot.is_primary', true)->first();
                @endphp
                <div class="rounded-xl border border-ugarte-border bg-white p-4 shadow-sm">
                    <p class="text-xs text-gray-400 mb-1">Programa</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $courseSection->course->program->name }}</p>
                </div>
                <div class="rounded-xl border border-ugarte-border bg-white p-4 shadow-sm">
                    <p class="text-xs text-gray-400 mb-1">Período</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $courseSection->academicPeriod->name }}</p>
                </div>
                <div class="rounded-xl border border-ugarte-border bg-white p-4 shadow-sm">
                    <p class="text-xs text-gray-400 mb-1">Docente principal</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $primaryTeacher?->name ?? '—' }}</p>
                </div>
                <div class="rounded-xl border border-ugarte-border bg-white p-4 shadow-sm">
                    <p class="text-xs text-gray-400 mb-1">Ocupación</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $enrollments->count() }} / {{ $courseSection->capacity }}</p>
                </div>
            </div>

            <div class="mt-4 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
                <dl class="grid sm:grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-400">Código sección</dt><dd class="font-medium text-gray-800">{{ $courseSection->section_code }}</dd></div>
                    <div><dt class="text-gray-400">Estado</dt>
                        <dd><span class="rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $courseSection->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $courseSection->status }}
                        </span></dd>
                    </div>
                    <div><dt class="text-gray-400">Inicio período</dt><dd class="font-medium text-gray-800">{{ $courseSection->academicPeriod->start_date->format('d/m/Y') }}</dd></div>
                    <div><dt class="text-gray-400">Fin período</dt><dd class="font-medium text-gray-800">{{ $courseSection->academicPeriod->end_date->format('d/m/Y') }}</dd></div>
                </dl>
            </div>

            {{-- Links rápidos --}}
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.course-sections.schedules.index', $courseSection) }}"
                   class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <x-erp.icon name="calendar-days" class="h-4 w-4" />
                    Gestionar horarios
                </a>
                <a href="{{ route('admin.class-sessions.index', ['course_section_id' => $courseSection->id]) }}"
                   class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <x-erp.icon name="clock" class="h-4 w-4" />
                    Ver sesiones
                </a>
            </div>
        </div>

        {{-- TAB: Horarios --}}
        <div x-show="tab === 'horarios'" x-cloak>
            <div class="rounded-xl border border-ugarte-border bg-white shadow-sm overflow-hidden">
                <div class="flex items-center justify-between border-b border-ugarte-border px-5 py-3">
                    <h3 class="font-semibold text-gray-800">Horarios de la sección</h3>
                    <a href="{{ route('admin.course-sections.schedules.create', $courseSection) }}"
                       class="flex items-center gap-1.5 rounded-lg bg-ugarte-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-ugarte-dark transition-colors">
                        + Nuevo horario
                    </a>
                </div>
                @if($courseSection->schedules->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-gray-400">No hay horarios registrados. Crea el primero.</p>
                @else
                <table class="min-w-full divide-y divide-ugarte-border text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Día</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Hora inicio</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Hora fin</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Aula</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Modalidad</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ugarte-border">
                        @foreach($courseSection->schedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $schedule->dayName }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $schedule->start_time }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $schedule->end_time }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $schedule->room ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $schedule->modality?->label() ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.course-sections.schedules.edit', [$courseSection, $schedule]) }}"
                                   class="text-xs text-ugarte-primary hover:underline">Editar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- TAB: Materiales --}}
        <div x-show="tab === 'materials'" x-cloak>

            {{-- Upload form --}}
            <div class="mb-5 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm"
                 x-data="{ showForm: false, type: 'file' }">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Materiales de la sección</h3>
                    <button @click="showForm = !showForm"
                            class="flex items-center gap-1.5 rounded-lg bg-ugarte-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-ugarte-dark transition-colors">
                        <span x-text="showForm ? '✕ Cancelar' : '+ Nuevo material'"></span>
                    </button>
                </div>

                <div x-show="showForm" x-transition class="mt-4 border-t border-ugarte-border pt-4">
                    <form action="{{ route('admin.course-sections.materials.store', $courseSection) }}"
                          method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Título <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required maxlength="200"
                                       class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary"
                                       placeholder="Ej: Introducción al corte de cabello" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                                <textarea name="description" rows="2"
                                          class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary"
                                          placeholder="Descripción breve del material..."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Visibilidad</label>
                                <select name="visibility"
                                        class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
                                    <option value="section">General de sección</option>
                                    <option value="private">Privado</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 mt-5">
                                <input type="checkbox" name="is_published" value="1" checked id="published_check"
                                       class="rounded border-ugarte-border text-ugarte-primary focus:ring-ugarte-primary" />
                                <label for="published_check" class="text-sm text-gray-600">Publicar inmediatamente</label>
                            </div>
                        </div>

                        {{-- Tipo de adjunto --}}
                        <div class="border-t border-ugarte-border pt-4">
                            <div class="flex gap-3 mb-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="type" value="file" class="text-ugarte-primary" />
                                    <span class="text-sm font-medium text-gray-700">Subir archivo</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="type" value="link" class="text-ugarte-primary" />
                                    <span class="text-sm font-medium text-gray-700">Enlace externo</span>
                                </label>
                            </div>

                            <div x-show="type === 'file'">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Archivo</label>
                                <input type="file" name="file"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.mp4,.zip"
                                       class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-ugarte-primary/10 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-ugarte-primary hover:file:bg-ugarte-primary/20" />
                                <p class="mt-1 text-xs text-gray-400">PDF, DOC, PPT, JPG, MP4, ZIP · máx. 50MB</p>
                            </div>

                            <div x-show="type === 'link'" class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">URL del enlace</label>
                                    <input type="url" name="link_url" placeholder="https://..."
                                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Texto del enlace</label>
                                    <input type="text" name="link_title" placeholder="Recurso de referencia..."
                                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                                Crear material
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Lista de materiales --}}
            <x-erp.material-list
                :materials="$materials"
                :can-manage="true"
                delete-route-prefix="admin.course-sections.materials.destroy"
                :route-params="[$courseSection]" />
        </div>

        {{-- TAB: Alumnos --}}
        <div x-show="tab === 'alumnos'" x-cloak>
            <div class="rounded-xl border border-ugarte-border bg-white shadow-sm overflow-hidden">
                <div class="flex items-center justify-between border-b border-ugarte-border px-5 py-3">
                    <h3 class="font-semibold text-gray-800">Alumnos matriculados</h3>
                    <span class="text-sm text-gray-400">{{ $enrollments->count() }} activos</span>
                </div>
                @if($enrollments->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-gray-400">No hay alumnos matriculados activos.</p>
                @else
                <table class="min-w-full divide-y divide-ugarte-border text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Alumno</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">N° Matrícula</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ugarte-border">
                        @foreach($enrollments as $enrollment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-ugarte-primary/10 text-xs font-semibold text-ugarte-primary">
                                        {{ $enrollment->alumno->initials }}
                                    </div>
                                    <p class="font-medium text-gray-800">{{ $enrollment->alumno->name }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $enrollment->alumno->email }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $enrollment->enrollment_number }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                    Activa
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
