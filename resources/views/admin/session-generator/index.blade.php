<x-layouts.app title="Generar Sesiones">
    <div class="mx-auto max-w-2xl rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Generar Sesiones de Clase</h2>
            <p class="mt-1 text-sm text-gray-500">Selecciona una sección y el rango de fechas para generar sesiones automáticamente a partir de los horarios configurados.</p>
        </div>

        <form action="{{ route('admin.session-generator.preview') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sección</label>
                <select name="course_section_id" required
                        class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary @error('course_section_id') border-red-400 @enderror">
                    <option value="">Seleccionar sección del período actual…</option>
                    @foreach($sections->groupBy('course.program.name') as $program => $secs)
                        <optgroup label="{{ $program }}">
                            @foreach($secs as $sec)
                                <option value="{{ $sec->id }}" @selected(old('course_section_id') == $sec->id)>
                                    {{ $sec->course->name }} — Secc. {{ $sec->section_code }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('course_section_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha desde</label>
                    <input type="date" name="from" required
                           value="{{ old('from', $currentPeriod?->start_date?->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha hasta</label>
                    <input type="date" name="to" required
                           value="{{ old('to', $currentPeriod?->end_date?->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                </div>
            </div>

            <div>
                <p class="block text-sm font-medium text-gray-700 mb-1">Fechas a excluir (opcional)</p>
                <p class="text-xs text-gray-400 mb-2">Agrega fechas que deben saltarse (feriados, días no lectivos).</p>
                <div x-data="{ dates: [] }">
                    <template x-for="(d, i) in dates" :key="i">
                        <div class="flex items-center gap-2 mb-2">
                            <input type="date" :name="'exclude_dates[' + i + ']'" x-model="dates[i]"
                                   class="rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                            <button type="button" @click="dates.splice(i,1)"
                                    class="text-red-400 hover:text-red-600 text-sm">✕</button>
                        </div>
                    </template>
                    <button type="button" @click="dates.push('')"
                            class="text-sm text-ugarte-primary hover:underline">+ Agregar fecha a excluir</button>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-ugarte-border">
                <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                    Ver preview →
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
