@props([
    'attendances',
    'totalSessions' => 0,
])

@php
    $present = $attendances->where('status', 'present')->count();
    $late    = $attendances->where('status', 'late')->count();
    $absent  = $attendances->where('status', 'absent')->count();
    $excused = $attendances->where('status', 'excused')->count();
    $attended = $present + $late;
    $total = $attendances->count();
    $rate = $total > 0 ? round(($attended / $total) * 100) : 0;
@endphp

<div>
    {{-- Resumen --}}
    <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-center">
            <p class="text-lg font-bold text-green-700">{{ $present }}</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-green-600">Presente</p>
        </div>
        <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2 text-center">
            <p class="text-lg font-bold text-yellow-700">{{ $late }}</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-yellow-600">Tarde</p>
        </div>
        <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-center">
            <p class="text-lg font-bold text-red-700">{{ $absent }}</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-red-600">Falta</p>
        </div>
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-center">
            <p class="text-lg font-bold text-blue-700">{{ $excused }}</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-600">Justificado</p>
        </div>
    </div>

    {{-- Barra de progreso --}}
    <div class="mb-4 rounded-lg border border-ugarte-border bg-gray-50 px-4 py-3">
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Asistencia general</span>
            <span class="font-semibold" @class([
                'text-green-700' => $rate >= 80,
                'text-yellow-700' => $rate >= 60 && $rate < 80,
                'text-red-700' => $rate < 60,
            ])>{{ $attended }} de {{ $total }} clases ({{ $rate }}%)</span>
        </div>
        <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-gray-200">
            <div class="h-full rounded-full transition-all @if($rate >= 80) bg-green-500 @elseif($rate >= 60) bg-yellow-500 @else bg-red-500 @endif"
                 style="width: {{ $rate }}%"></div>
        </div>
    </div>

    {{-- Lista cronológica --}}
    @if($attendances->isNotEmpty())
        <h4 class="mb-2 text-sm font-semibold text-gray-700">Detalle por clase</h4>
        <div class="space-y-1.5">
            @foreach($attendances->sortBy('classSession.session_number') as $att)
                <div class="flex items-center gap-3 rounded-md px-3 py-2 text-sm
                    @switch($att->status)
                        @case('present') bg-green-50 @break
                        @case('late') bg-yellow-50 @break
                        @case('absent') bg-red-50 @break
                        @case('excused') bg-blue-50 @break
                        @default bg-gray-50
                    @endswitch
                ">
                    <span class="shrink-0 text-base">
                        @switch($att->status)
                            @case('present') ✔ @break
                            @case('late') ⏰ @break
                            @case('absent') ❌ @break
                            @case('excused') 📋 @break
                        @endswitch
                    </span>
                    <span class="flex-1 font-medium text-gray-800">
                        Clase {{ $att->classSession->session_number ?? '?' }}
                        @if($att->classSession?->title)
                            — {{ $att->classSession->title }}
                        @endif
                    </span>
                    <span class="shrink-0 text-xs text-gray-500">
                        {{ $att->classSession?->starts_at?->format('d/m') }}
                    </span>
                    <span class="shrink-0 text-xs font-medium {{ \App\Enums\AttendanceStatus::from($att->status)->badgeClass() }} rounded-full px-1.5 py-0.5">
                        {{ \App\Enums\AttendanceStatus::from($att->status)->label() }}
                    </span>
                </div>
            @endforeach
        </div>
    @else
        <p class="py-6 text-center text-sm text-gray-400">Aún no hay registros de asistencia.</p>
    @endif
</div>
