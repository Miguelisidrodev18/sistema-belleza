<x-layouts.app title="Dashboard">
    @php
    use App\Academic\ClassSession;
    $teacherId = auth()->id();
    $todaySessions = ClassSession::with(['courseSection.course', 'meeting'])
        ->whereHas('courseSection', function ($q) use ($teacherId) {
            $q->whereHas('teachers', fn($t) => $t->where('users.id', $teacherId)->where('course_section_teachers.is_primary', true))
              ->whereHas('academicPeriod', fn($p) => $p->where('is_current', true));
        })
        ->today()
        ->orderBy('starts_at')
        ->get();
    @endphp

    <div class="rounded-xl border border-ugarte-border bg-white p-5 shadow-sm mb-5">
        <h2 class="text-base font-semibold text-gray-900">
            Buenos {{ now()->hour < 12 ? 'días' : (now()->hour < 18 ? 'tardes' : 'noches') }},
            {{ explode(' ', auth()->user()->name)[0] }}
        </h2>
        <p class="text-sm text-gray-400">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
    </div>

    @if($todaySessions->isNotEmpty())
    <div class="mb-5">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">
            Hoy — {{ $todaySessions->count() }} {{ $todaySessions->count() === 1 ? 'clase' : 'clases' }}
        </h3>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach($todaySessions as $session)
            <div class="rounded-xl border border-ugarte-border bg-white p-4 shadow-sm">
                <p class="font-semibold text-gray-800">{{ $session->title ?? "Clase #{$session->session_number}" }}</p>
                <p class="text-sm text-gray-500">{{ $session->courseSection->course->name }} · {{ $session->starts_at->format('H:i') }}</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="{{ route('docente.class-sessions.show', $session) }}"
                       class="rounded-lg bg-ugarte-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-ugarte-dark">
                        Tomar asistencia
                    </a>
                    @if($session->meeting?->meeting_url)
                    <a href="{{ $session->meeting->meeting_url }}" target="_blank"
                       class="rounded-lg border border-ugarte-border px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50">
                        Abrir {{ $session->meeting->platform->label() }}
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="rounded-xl border border-ugarte-border bg-white p-6 mb-5 text-center">
        <p class="text-sm text-gray-400">No tienes clases programadas para hoy.</p>
    </div>
    @endif

    <div class="flex gap-3">
        <a href="{{ route('docente.sections.index') }}"
           class="rounded-lg border border-ugarte-border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Mis Secciones
        </a>
        <a href="{{ route('docente.class-sessions.index') }}"
           class="rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
            Todas mis clases
        </a>
    </div>
</x-layouts.app>
