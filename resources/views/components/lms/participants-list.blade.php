@props([
    'enrollments',
    'primaryTeacher' => null,
    'role' => 'alumno',
])

<div>
    {{-- Docente responsable --}}
    @if($primaryTeacher)
        <div class="mb-4 flex items-center gap-3 rounded-lg bg-ugarte-primary/5 px-4 py-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-ugarte-primary/10 text-sm font-semibold text-ugarte-primary">
                {{ $primaryTeacher->initials }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $primaryTeacher->name }}</p>
                <p class="text-xs text-ugarte-primary font-medium">Docente responsable</p>
            </div>
        </div>
    @endif

    {{-- Contador --}}
    <div class="mb-3 flex items-center justify-between">
        <h4 class="text-sm font-semibold text-gray-700">
            Participantes ({{ $enrollments->count() }})
        </h4>
    </div>

    {{-- Lista --}}
    @if($enrollments->isNotEmpty())
        <div class="divide-y divide-ugarte-border/50 rounded-lg border border-ugarte-border">
            @foreach($enrollments as $enrollment)
                @php $alumno = $enrollment->alumno; @endphp
                <div class="flex items-center gap-3 px-4 py-2.5">
                    {{-- Avatar con iniciales --}}
                    <div class="relative flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-600">
                        {{ $alumno->initials }}
                        {{-- Indicador de estado --}}
                        <span class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 rounded-full border-2 border-white
                            @switch($enrollment->status->value ?? $enrollment->status)
                                @case('activa') bg-green-500 @break
                                @case('suspendida') bg-yellow-400 @break
                                @default bg-gray-300
                            @endswitch
                        "></span>
                    </div>

                    {{-- Info --}}
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">{{ $alumno->name }}</p>

                        @if($role === 'docente' || $role === 'admin')
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="{{ $enrollment->status->badgeClass() }} inline-flex rounded-full px-1.5 py-0.5 text-[10px] font-semibold">
                                    {{ $enrollment->status->label() }}
                                </span>
                                @if(isset($enrollment->attendance_rate))
                                    <span>Asistencia: {{ $enrollment->attendance_rate }}%</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($role === 'admin')
                        <div class="text-xs text-gray-400 font-mono">{{ $alumno->dni ?? '—' }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="py-6 text-center text-sm text-gray-400">No hay participantes matriculados.</p>
    @endif
</div>
