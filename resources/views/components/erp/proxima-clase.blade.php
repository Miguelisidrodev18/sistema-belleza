@php
use App\Academic\ClassSession;
use App\Models\Enrollment;

$alumnoId = auth()->id();
$enrollmentSectionIds = Enrollment::where('alumno_id', $alumnoId)
    ->where('status', 'activa')
    ->pluck('course_section_id');

$nextSession = ClassSession::with(['courseSection.course', 'meeting'])
    ->whereIn('course_section_id', $enrollmentSectionIds)
    ->where('starts_at', '>', now())
    ->where('starts_at', '<=', now()->addHours(2))
    ->whereNotIn('status', ['cancelled'])
    ->orderBy('starts_at')
    ->first();
@endphp

@if($nextSession)
<div class="mb-5 rounded-xl border-2 border-ugarte-primary bg-ugarte-light p-4 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-ugarte-primary">Próxima clase</p>
            <p class="mt-0.5 font-semibold text-gray-800">
                {{ $nextSession->title ?? "Clase #{$nextSession->session_number}" }} —
                {{ $nextSession->courseSection->course->name }}
            </p>
            <p class="text-sm text-gray-500">
                {{ $nextSession->starts_at->format('H:i') }} – {{ $nextSession->ends_at->format('H:i') }}
                @if($nextSession->effectiveRoom)
                    · {{ $nextSession->effectiveRoom }}
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            @if($nextSession->meeting?->meeting_url)
            <a href="{{ $nextSession->meeting->meeting_url }}" target="_blank"
               class="rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                Unirse →
            </a>
            @endif
            <span class="rounded-lg border border-ugarte-primary px-4 py-2 text-sm font-semibold text-ugarte-primary"
                  id="countdown-proxima"></span>
        </div>
    </div>
</div>

<script>
(function () {
    const starts = new Date('{{ $nextSession->starts_at->toIso8601String() }}');
    const el = document.getElementById('countdown-proxima');
    function update() {
        const diff = Math.max(0, Math.floor((starts - Date.now()) / 1000));
        const h = Math.floor(diff / 3600), m = Math.floor((diff % 3600) / 60), s = diff % 60;
        el.textContent = h > 0 ? `${h}h ${m}m` : `${m}m ${s}s`;
    }
    update();
    setInterval(update, 1000);
})();
</script>
@endif
