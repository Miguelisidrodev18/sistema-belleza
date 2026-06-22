<x-layouts.app :title="$courseSection->course->name">
    <x-slot:actions>
        <a href="{{ route('alumno.enrollments.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Mis Matrículas
        </a>
    </x-slot:actions>

    {{-- Header --}}
    <div class="mb-5 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">{{ $courseSection->course->name }}</h2>
        <p class="text-sm text-gray-500">
            Sección {{ $courseSection->section_code }}
            · {{ $courseSection->course->program->name }}
            · {{ $courseSection->academicPeriod->name }}
        </p>
        @if($teacher)
            <p class="mt-1 text-sm text-gray-500">Docente: <span class="font-medium text-gray-700">{{ $teacher->name }}</span></p>
        @endif
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: '{{ request('tab', 'general') }}' }">
        <div class="mb-5 flex gap-1 overflow-x-auto rounded-lg bg-gray-100 p-1">
            @foreach([
                'general' => 'General',
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
                            {{ $sectionMaterials->count() }}
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

        {{-- Tab: General --}}
        <div x-show="tab === 'general'" x-transition class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Programa</p>
                    <p class="text-sm font-medium text-gray-900">{{ $courseSection->course->program->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Curso</p>
                    <p class="text-sm font-medium text-gray-900">{{ $courseSection->course->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Período Académico</p>
                    <p class="text-sm font-medium text-gray-900">{{ $courseSection->academicPeriod->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Docente</p>
                    <p class="text-sm font-medium text-gray-900">{{ $teacher?->name ?? 'Sin asignar' }}</p>
                </div>
            </div>

            {{-- Horarios --}}
            @if($courseSection->schedules->isNotEmpty())
                <div class="mt-6">
                    <h4 class="mb-2 text-sm font-semibold text-gray-700">Horarios</h4>
                    <div class="space-y-1.5">
                        @foreach($courseSection->schedules->where('is_active', true) as $schedule)
                            <div class="flex items-center gap-3 rounded-md bg-gray-50 px-3 py-2 text-sm">
                                <span class="font-medium text-gray-800">{{ $schedule->day_name }}</span>
                                <span class="text-gray-500">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                @if($schedule->room)
                                    <span class="text-gray-400">· {{ $schedule->room }}</span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $schedule->modality->label() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Tab: Materiales --}}
        <div x-show="tab === 'materials'" x-transition>
            @if($sectionMaterials->isNotEmpty())
                <div class="space-y-3">
                    @foreach($sectionMaterials as $material)
                        <x-lms.material-card :material="$material" role="alumno" />
                    @endforeach
                </div>
            @else
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
