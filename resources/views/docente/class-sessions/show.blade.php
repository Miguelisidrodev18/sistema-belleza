<x-layouts.app title="Clase #{{ $classSession->session_number }}">
    <x-slot:actions>
        <a href="{{ route('docente.class-sessions.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Mis Clases
        </a>
    </x-slot:actions>

    {{-- Title --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-900">
            {{ $classSession->title ?? "Clase #{$classSession->session_number}" }}
        </h1>
        <p class="text-sm text-gray-500">
            {{ $classSession->courseSection->course->name }} ·
            {{ $classSession->starts_at->format('d/m/Y') }} ·
            {{ $classSession->starts_at->format('H:i') }}–{{ $classSession->ends_at->format('H:i') }}
        </p>
    </div>

    <div x-data="{ tab: 'info' }">

        {{-- Tab bar --}}
        <div class="flex gap-1 border-b border-ugarte-border mb-5">
            @foreach([
                ['id' => 'info',         'label' => 'Información'],
                ['id' => 'asistencia',   'label' => 'Asistencia'],
                ['id' => 'materials',    'label' => 'Materiales'],
                ['id' => 'meeting',      'label' => 'Meeting'],
                ['id' => 'participants', 'label' => 'Participantes'],
            ] as $t)
            <button @click="tab = '{{ $t['id'] }}'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors"
                    :class="tab === '{{ $t['id'] }}' ? 'border-ugarte-primary text-ugarte-primary' : 'border-transparent text-gray-500 hover:text-gray-800'">
                {{ $t['label'] }}
                @if($t['id'] === 'asistencia' && $stats['total'] > 0)
                <span class="ml-1.5 rounded-full bg-green-100 px-1.5 text-[10px] font-bold text-green-700">{{ $stats['rate'] }}%</span>
                @endif
                @if($t['id'] === 'materials')
                <span class="ml-1.5 rounded-full bg-ugarte-primary/10 px-1.5 text-[10px] font-bold text-ugarte-primary">{{ $sessionMaterials->count() }}</span>
                @endif
                @if($t['id'] === 'participants')
                <span class="ml-1.5 rounded-full bg-gray-100 px-1.5 text-[10px] font-bold text-gray-600">{{ $enrollments->count() }}</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- TAB: Información --}}
        <div x-show="tab === 'info'" x-cloak>
            <div class="grid gap-5 lg:grid-cols-2">
                {{-- Info card --}}
                <div class="rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-800 mb-3">Detalles de la sesión</h2>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="text-gray-400">Curso</dt><dd class="text-gray-700">{{ $classSession->courseSection->course->name }}</dd></div>
                        <div><dt class="text-gray-400">Sección</dt><dd class="text-gray-700">{{ $classSession->courseSection->section_code }}</dd></div>
                        <div><dt class="text-gray-400">Fecha</dt><dd class="text-gray-700">{{ $classSession->starts_at->format('d/m/Y') }}</dd></div>
                        <div><dt class="text-gray-400">Hora</dt><dd class="text-gray-700">{{ $classSession->starts_at->format('H:i') }} – {{ $classSession->ends_at->format('H:i') }}</dd></div>
                        @if($classSession->effectiveRoom)
                        <div><dt class="text-gray-400">Aula</dt><dd class="text-gray-700">{{ $classSession->effectiveRoom }}</dd></div>
                        @endif
                        <div>
                            <dt class="text-gray-400">Estado</dt>
                            <dd><span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classSession->status->badgeClass() }}">{{ $classSession->status->label() }}</span></dd>
                        </div>
                    </dl>
                </div>

                {{-- Update form --}}
                <div class="rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Actualizar sesión</p>
                    <form action="{{ route('docente.class-sessions.update', $classSession) }}" method="POST" class="space-y-3">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Estado</label>
                            <select name="status" class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
                                @foreach(\App\Enums\ClassSessionStatus::cases() as $st)
                                    <option value="{{ $st->value }}" @selected($classSession->status === $st)>{{ $st->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Enlace de videollamada</label>
                            <input type="url" name="meeting_url" value="{{ $classSession->meeting?->meeting_url }}"
                                   placeholder="https://zoom.us/j/..."
                                   class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Notas de sesión</label>
                            <textarea name="notes" rows="2"
                                      class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">{{ $classSession->notes }}</textarea>
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TAB: Asistencia --}}
        <div x-show="tab === 'asistencia'" x-cloak>
            <div class="rounded-xl border border-ugarte-border bg-white shadow-sm">
                <div class="border-b border-ugarte-border px-5 py-4 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Registro de asistencia</h3>
                    <span class="text-sm text-gray-400">{{ $enrollments->count() }} alumnos</span>
                </div>

                @if($stats['total'] > 0)
                <div class="border-b border-ugarte-border px-5 py-3 bg-gray-50">
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <div><p class="text-lg font-bold text-green-600">{{ $stats['present'] }}</p><p class="text-xs text-gray-400">Presentes</p></div>
                        <div><p class="text-lg font-bold text-yellow-600">{{ $stats['late'] }}</p><p class="text-xs text-gray-400">Tarde</p></div>
                        <div><p class="text-lg font-bold text-red-600">{{ $stats['absent'] }}</p><p class="text-xs text-gray-400">Ausentes</p></div>
                        <div><p class="text-lg font-bold text-blue-600">{{ $stats['rate'] }}%</p><p class="text-xs text-gray-400">Tasa</p></div>
                    </div>
                </div>
                @endif

                @if($enrollments->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-gray-400">No hay alumnos matriculados activos.</p>
                @else
                <form action="{{ route('docente.class-sessions.attendance', $classSession) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-ugarte-border text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Alumno</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Hora llegada</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Hora salida</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Notas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ugarte-border">
                                @foreach($enrollments as $enrollment)
                                @php $att = $classSession->attendances->firstWhere('enrollment_id', $enrollment->id); @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-800">{{ $enrollment->alumno->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $enrollment->enrollment_number }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <select name="attendance[{{ $enrollment->id }}][status]"
                                                class="rounded border-ugarte-border px-2 py-1 text-xs focus:ring-1 focus:ring-ugarte-primary">
                                            @foreach(\App\Enums\AttendanceStatus::cases() as $as)
                                                <option value="{{ $as->value }}" @selected(($att?->status ?? \App\Enums\AttendanceStatus::Absent) === $as)>{{ $as->label() }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="time" name="attendance[{{ $enrollment->id }}][arrival_time]"
                                               value="{{ $att?->arrival_time }}"
                                               class="rounded border-ugarte-border px-2 py-1 text-xs focus:ring-1 focus:ring-ugarte-primary" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="time" name="attendance[{{ $enrollment->id }}][departure_time]"
                                               value="{{ $att?->departure_time }}"
                                               class="rounded border-ugarte-border px-2 py-1 text-xs focus:ring-1 focus:ring-ugarte-primary" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="attendance[{{ $enrollment->id }}][notes]"
                                               value="{{ $att?->notes }}" placeholder="Obs."
                                               class="w-28 rounded border-ugarte-border px-2 py-1 text-xs focus:ring-1 focus:ring-ugarte-primary" />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-ugarte-border px-5 py-3">
                        <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                            Guardar asistencia
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>

        {{-- TAB: Materiales --}}
        <div x-show="tab === 'materials'" x-cloak>

            {{-- Upload form --}}
            <div class="mb-5 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm"
                 x-data="{ showForm: false, type: 'file' }">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Materiales de la sesión</h3>
                    <button @click="showForm = !showForm"
                            class="flex items-center gap-1.5 rounded-lg bg-ugarte-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-ugarte-dark transition-colors">
                        <span x-text="showForm ? '✕ Cancelar' : '+ Subir material'"></span>
                    </button>
                </div>

                <div x-show="showForm" x-transition class="mt-4 border-t border-ugarte-border pt-4">
                    <form action="{{ route('docente.class-sessions.materials.store', $classSession) }}"
                          method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Título <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required maxlength="200"
                                       class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary"
                                       placeholder="Ej: Presentación de la sesión" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                                <textarea name="description" rows="2"
                                          class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary"></textarea>
                            </div>
                        </div>

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
                                <input type="file" name="file"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.mp4,.zip"
                                       class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-ugarte-primary/10 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-ugarte-primary hover:file:bg-ugarte-primary/20" />
                                <p class="mt-1 text-xs text-gray-400">PDF, DOC, PPT, JPG, MP4, ZIP · máx. 50MB</p>
                            </div>
                            <div x-show="type === 'link'" class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <input type="url" name="link_url" placeholder="https://..."
                                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                                </div>
                                <div>
                                    <input type="text" name="link_title" placeholder="Texto del enlace..."
                                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                            Subir material
                        </button>
                    </form>
                </div>
            </div>

            <x-erp.material-list
                :materials="$sessionMaterials"
                :can-manage="true"
                delete-route-prefix="docente.class-sessions.materials.destroy"
                :route-params="[$classSession]" />
        </div>

        {{-- TAB: Meeting --}}
        <div x-show="tab === 'meeting'" x-cloak>
            <div class="rounded-xl border border-ugarte-border bg-white p-5 shadow-sm max-w-lg">
                @if($classSession->meeting?->meeting_url)
                <h3 class="font-semibold text-gray-800 mb-4">Videollamada</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Plataforma</dt>
                        <dd class="font-medium text-gray-700">{{ $classSession->meeting->platform->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Enlace</dt>
                        <dd>
                            <a href="{{ $classSession->meeting->meeting_url }}" target="_blank"
                               class="text-ugarte-primary hover:underline break-all text-sm">
                                {{ $classSession->meeting->meeting_url }}
                            </a>
                        </dd>
                    </div>
                    @if($classSession->meeting->passcode)
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Contraseña</dt>
                        <dd class="font-mono bg-gray-50 rounded px-2 py-1 text-sm inline-block">{{ $classSession->meeting->passcode }}</dd>
                    </div>
                    @endif
                    @if($classSession->meeting->recording_url)
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Grabación</dt>
                        <dd>
                            <a href="{{ $classSession->meeting->recording_url }}" target="_blank"
                               class="text-ugarte-primary hover:underline text-sm">
                                Ver grabación
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>
                <a href="{{ $classSession->meeting->meeting_url }}" target="_blank"
                   class="mt-5 flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                    Unirse a {{ $classSession->meeting->platform->label() }}
                </a>
                @else
                <p class="text-sm text-gray-400 text-center py-8">
                    No hay videollamada configurada para esta sesión.<br>
                    Puedes agregar un enlace desde la pestaña "Información".
                </p>
                @endif
            </div>
        </div>

        {{-- TAB: Participantes --}}
        <div x-show="tab === 'participants'" x-cloak>
            <div class="rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
                <x-lms.participants-list
                    :enrollments="$enrollments"
                    :primaryTeacher="$classSession->courseSection->primaryTeacher()"
                    role="docente"
                />
            </div>
        </div>
    </div>
</x-layouts.app>
