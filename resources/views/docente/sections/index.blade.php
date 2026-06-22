<x-layouts.app title="Mis Secciones">
    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($sections->isEmpty())
        <div class="rounded-xl border border-ugarte-border bg-white p-10 text-center text-sm text-gray-400 shadow-sm">
            No tienes secciones asignadas en el período actual.
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($sections as $section)
                @php
                    $enrolled  = $section->enrolledCount();
                    $pct       = $section->capacity > 0 ? round($enrolled / $section->capacity * 100) : 0;
                    $colorClass = $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-yellow-400' : 'bg-green-400');
                @endphp
                <div class="rounded-xl border border-ugarte-border bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="mb-1 flex items-start justify-between gap-2">
                        <span class="text-xs font-medium text-ugarte-primary">{{ $section->academicPeriod->name }}</span>
                        <span class="text-xs text-gray-400 font-mono">Sec. {{ $section->section_code }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 leading-tight">{{ $section->course->name }}</h3>
                    <p class="mt-0.5 text-xs text-gray-500">{{ $section->course->program->name }}</p>

                    <div class="mt-4">
                        <div class="mb-1 flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $enrolled }} / {{ $section->capacity }} alumnos</span>
                            <span>{{ $pct }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full transition-all {{ $colorClass }}" style="width: {{ min($pct, 100) }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('docente.sections.students', $section) }}"
                           class="flex items-center gap-1.5 rounded-lg border border-ugarte-border px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                            <x-erp.icon name="users" class="h-3.5 w-3.5" />
                            Ver alumnos
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
