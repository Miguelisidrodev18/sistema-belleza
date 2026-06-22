<x-layouts.app title="Preview — Generar Sesiones">
    <div class="mb-4 rounded-xl border border-ugarte-border bg-white p-4 shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <p class="text-sm text-gray-500">Sección</p>
                <p class="font-medium text-gray-800">{{ $section->course->name }} — Secc. {{ $section->section_code }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Rango</p>
                <p class="font-medium text-gray-800">{{ $from->format('d/m/Y') }} → {{ $to->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Sesiones a crear</p>
                <p class="font-medium text-gray-800">{{ $candidates->count() }}</p>
            </div>
            @if($conflicts > 0)
            <div>
                <p class="text-sm text-gray-500">Conflictos</p>
                <p class="font-medium text-red-600">{{ $conflicts }} ⚠</p>
            </div>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.session-generator.simulate') }}" method="POST">
        @csrf
        <input type="hidden" name="course_section_id" value="{{ $section->id }}">
        <input type="hidden" name="from" value="{{ $from->toDateString() }}">
        <input type="hidden" name="to" value="{{ $to->toDateString() }}">
        @foreach($excludeDates as $ed)
            <input type="hidden" name="exclude_dates[]" value="{{ $ed }}">
        @endforeach

        <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
            <table class="min-w-full divide-y divide-ugarte-border text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left">
                            <input type="checkbox" id="selectAll"
                                   class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary"
                                   onclick="document.querySelectorAll('.row-exclude').forEach(cb => cb.checked = !this.checked)">
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Fecha</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Día</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Hora</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Aula</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Conflictos</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Override</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ugarte-border">
                    @foreach($candidates as $item)
                    <tr class="{{ $item->has_conflict ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                        <td class="px-3 py-2">
                            <input type="checkbox" name="excluded_indices[]" value="{{ $item->index }}"
                                   class="row-exclude h-4 w-4 rounded border-gray-300">
                        </td>
                        <td class="px-3 py-2 font-mono font-semibold text-ugarte-primary">{{ $item->session_number }}</td>
                        <td class="px-3 py-2">{{ $item->starts_at->format('d/m/Y') }}</td>
                        <td class="px-3 py-2">{{ $item->starts_at->locale('es')->isoFormat('ddd') }}</td>
                        <td class="px-3 py-2">{{ $item->starts_at->format('H:i') }} – {{ $item->ends_at->format('H:i') }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $item->schedule->room ?? '—' }}</td>
                        <td class="px-3 py-2">
                            @if($item->has_conflict)
                                <div class="space-y-1">
                                    @foreach(array_filter($item->conflicts) as $type => $msg)
                                        <p class="text-xs text-red-600">⚠ {{ $msg }}</p>
                                    @endforeach
                                    <label class="flex items-center gap-1 text-xs text-gray-600">
                                        <input type="checkbox" name="overrides[{{ $item->index }}][ignore_conflict]" value="1"
                                               class="h-3 w-3">
                                        Ignorar y crear igual
                                    </label>
                                </div>
                            @else
                                <span class="text-green-600 text-xs">OK</span>
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            <input type="text" name="overrides[{{ $item->index }}][room]"
                                   placeholder="Nueva aula"
                                   class="w-24 rounded border-ugarte-border px-2 py-1 text-xs focus:ring-1 focus:ring-ugarte-primary" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                Ver simulación →
            </button>
            <a href="{{ route('admin.session-generator.index') }}"
               class="rounded-lg border border-ugarte-border px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                ← Volver
            </a>
            <p class="text-sm text-gray-500">Marca las filas para omitirlas</p>
        </div>
    </form>
</x-layouts.app>
