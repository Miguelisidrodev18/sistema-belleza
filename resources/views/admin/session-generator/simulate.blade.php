<x-layouts.app title="Simulación — Confirmar Generación">
    <div class="mx-auto max-w-xl rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-semibold text-gray-900">Simulación de Generación</h2>

        <div class="mb-6 divide-y divide-ugarte-border rounded-lg border border-ugarte-border">
            <div class="flex items-center justify-between px-4 py-3">
                <span class="text-sm text-gray-600">Sesiones a crear</span>
                <span class="text-lg font-bold text-ugarte-primary">{{ $toCreate }}</span>
            </div>
            <div class="flex items-center justify-between px-4 py-3">
                <span class="text-sm text-gray-600">Conflictos detectados</span>
                <span class="text-lg font-semibold {{ $conflictsDetected > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $conflictsDetected }}</span>
            </div>
            <div class="flex items-center justify-between px-4 py-3">
                <span class="text-sm text-gray-600">Aulas cambiadas por override</span>
                <span class="text-lg font-semibold text-blue-600">{{ $roomOverrides }}</span>
            </div>
            <div class="flex items-center justify-between px-4 py-3">
                <span class="text-sm text-gray-600">Conflictos ignorados</span>
                <span class="text-lg font-semibold {{ $conflictsIgnored > 0 ? 'text-yellow-600' : 'text-gray-400' }}">{{ $conflictsIgnored }}</span>
            </div>
        </div>

        <form action="{{ route('admin.session-generator.generate') }}" method="POST">
            @csrf
            <input type="hidden" name="course_section_id" value="{{ $section->id }}">
            <input type="hidden" name="from" value="{{ $from->toDateString() }}">
            <input type="hidden" name="to" value="{{ $to->toDateString() }}">
            @foreach($excludeDates as $ed)
                <input type="hidden" name="exclude_dates[]" value="{{ $ed }}">
            @endforeach
            @foreach($excludedIndices as $idx)
                <input type="hidden" name="excluded_indices[]" value="{{ $idx }}">
            @endforeach
            @foreach($overrides as $idx => $ov)
                @foreach($ov as $key => $val)
                    <input type="hidden" name="overrides[{{ $idx }}][{{ $key }}]" value="{{ $val }}">
                @endforeach
            @endforeach

            <div class="flex gap-3">
                <button type="submit"
                        class="rounded-lg bg-ugarte-primary px-6 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                    Confirmar y generar
                </button>
                <a href="javascript:history.back()"
                   class="rounded-lg border border-ugarte-border px-5 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    ← Volver y corregir
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
