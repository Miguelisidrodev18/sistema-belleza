<x-layouts.app :title="'Clase #' . $classSession->session_number">
    <x-slot:actions>
        <a href="{{ route('alumno.calendar.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Calendario
        </a>
    </x-slot:actions>

    @php
        $section = $classSession->courseSection;
        $teacher = $section->primaryTeacher();
        $meeting = $classSession->meeting;
    @endphp

    {{-- Header --}}
    <div class="mb-5 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $classSession->title ?? 'Clase #' . $classSession->session_number }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $section->course->name }} — Sec. {{ $section->section_code }}
                    · {{ $section->course->program->name }}
                </p>
            </div>
            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $classSession->status->badgeClass() }}">
                {{ $classSession->status->value === 'scheduled' ? 'Programada' : ($classSession->status->value === 'in_progress' ? 'En curso' : ($classSession->status->value === 'completed' ? 'Completada' : 'Cancelada')) }}
            </span>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: '{{ request('tab', 'info') }}' }">
        <div class="mb-5 flex gap-1 overflow-x-auto rounded-lg bg-gray-100 p-1">
            @foreach([
                'info' => 'Información',
                'meeting' => 'Meeting',
                'materials' => 'Materiales',
                'participants' => 'Participantes',
                'attendance' => 'Mi Asistencia',
            ] as $key => $label)
                <button @click="tab = '{{ $key }}'" type="button"
                        :class="tab === '{{ $key }}' ? 'bg-white text-ugarte-primary shadow-sm font-medium' : 'text-gray-500 hover:text-gray-700'"
                        class="whitespace-nowrap rounded-md px-4 py-2 text-sm transition-colors">
                    {{ $label }}
                    @if($key === 'materials')
                        <span class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.5 text-[10px] font-semibold text-gray-600"
                              :class="tab === 'materials' ? 'bg-ugarte-primary/10 text-ugarte-primary' : ''">
                            {{ $sessionMaterials->count() + $sectionMaterials->count() }}
                        </span>
                    @endif
                    @if($key === 'participants')
                        <span class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.5 text-[10px] font-semibold text-gray-600"
                              :class="tab === 'participants' ? 'bg-ugarte-primary/10 text-ugarte-primary' : ''">
                            {{ $enrollments->count() }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Tab: Información --}}
        <div x-show="tab === 'info'" x-transition class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Fecha</p>
                    <p class="text-sm font-medium text-gray-900">{{ $classSession->starts_at->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Horario</p>
                    <p class="text-sm font-medium text-gray-900">{{ $classSession->starts_at->format('H:i') }} — {{ $classSession->ends_at->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Aula</p>
                    <p class="text-sm font-medium text-gray-900">{{ $classSession->effectiveRoom ?? 'Sin asignar' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Modalidad</p>
                    <p class="text-sm font-medium text-gray-900">{{ $classSession->effectiveModality?->label() ?? 'Sin definir' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Docente</p>
                    <p class="text-sm font-medium text-gray-900">{{ $teacher?->name ?? 'Sin asignar' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Período</p>
                    <p class="text-sm font-medium text-gray-900">{{ $section->academicPeriod->name }}</p>
                </div>
            </div>
            @if($classSession->notes)
                <div class="mt-4 rounded-lg bg-gray-50 p-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Notas</p>
                    <p class="text-sm text-gray-700">{{ $classSession->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Tab: Meeting --}}
        <div x-show="tab === 'meeting'" x-transition class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            @if($meeting && $meeting->meeting_url)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex rounded-full bg-ugarte-primary/10 px-2.5 py-1 text-xs font-semibold text-ugarte-primary uppercase">
                            {{ $meeting->platform->value ?? $meeting->platform }}
                        </span>
                        @if($meeting->passcode)
                            <span class="text-xs text-gray-500">Código: <span class="font-mono font-semibold">{{ $meeting->passcode }}</span></span>
                        @endif
                    </div>

                    <a href="{{ $meeting->meeting_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-lg bg-ugarte-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
                        <x-erp.icon name="link" class="h-4 w-4" />
                        Unirse a la clase
                    </a>

                    @if($meeting->recording_url && $classSession->status->value === 'completed')
                        <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <p class="text-sm font-semibold text-blue-800 mb-1">Grabación disponible</p>
                            <a href="{{ $meeting->recording_url }}" target="_blank" rel="noopener"
                               class="text-sm font-medium text-blue-700 hover:underline">
                                Ver grabación
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="py-8 text-center text-sm text-gray-400">No hay enlace de videollamada para esta sesión.</p>
            @endif
        </div>

        {{-- Tab: Materiales --}}
        <div x-show="tab === 'materials'" x-transition class="space-y-4">
            @if($sessionMaterials->isNotEmpty())
                <div>
                    <h3 class="mb-3 text-sm font-semibold text-gray-700">Materiales de esta sesión</h3>
                    <div class="space-y-3">
                        @foreach($sessionMaterials as $material)
                            <x-lms.material-card :material="$material" role="alumno" />
                        @endforeach
                    </div>
                </div>
            @endif

            @if($sectionMaterials->isNotEmpty())
                <div>
                    <h3 class="mb-3 text-sm font-semibold text-gray-700">Materiales generales del curso</h3>
                    <div class="space-y-3">
                        @foreach($sectionMaterials as $material)
                            <x-lms.material-card :material="$material" role="alumno" />
                        @endforeach
                    </div>
                </div>
            @endif

            @if($sessionMaterials->isEmpty() && $sectionMaterials->isEmpty())
                <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
                    <p class="py-4 text-center text-sm text-gray-400">No hay materiales disponibles.</p>
                </div>
            @endif
        </div>

        {{-- Tab: Participantes --}}
        <div x-show="tab === 'participants'" x-transition class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <x-lms.participants-list
                :enrollments="$enrollments"
                :primaryTeacher="$teacher"
                role="alumno"
            />
        </div>

        {{-- Tab: Mi Asistencia --}}
        <div x-show="tab === 'attendance'" x-transition class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Mi Asistencia</h3>
            <x-lms.attendance-summary
                :attendances="$myAttendances"
                :totalSessions="$totalSessions"
            />
        </div>
    </div>
</x-layouts.app>
