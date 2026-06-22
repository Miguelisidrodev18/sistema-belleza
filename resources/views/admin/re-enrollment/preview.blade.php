<x-layouts.app title="Preview Re-matrícula">
    <x-slot:actions>
        <a href="{{ route('admin.re-enrollment.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Cambiar períodos
        </a>
    </x-slot:actions>

    <div class="mb-4 rounded-xl border border-ugarte-border bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3 text-sm text-gray-600">
            <span class="font-medium">Origen:</span>
            <span class="rounded bg-gray-100 px-2 py-0.5 font-semibold text-gray-800">{{ $source->name }}</span>
            <x-erp.icon name="arrow-right" class="h-4 w-4 text-gray-400" />
            <span class="font-medium">Destino:</span>
            <span class="rounded bg-ugarte-primary/10 px-2 py-0.5 font-semibold text-ugarte-primary">{{ $target->name }}</span>
            <span class="ml-auto text-gray-400">{{ $eligible->count() }} alumno(s) elegibles</span>
        </div>
    </div>

    @if($eligible->isEmpty())
        <div class="rounded-xl border border-ugarte-border bg-white p-10 text-center text-sm text-gray-400 shadow-sm">
            No hay alumnos elegibles para re-matrícula entre estos períodos.
        </div>
    @else
        <form method="POST" action="{{ route('admin.re-enrollment.execute') }}">
            @csrf
            <input type="hidden" name="target_period_id" value="{{ $target->id }}">

            <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-ugarte-border bg-gray-50 px-4 py-3">
                    <input type="checkbox" id="select-all"
                           class="rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary"
                           onchange="document.querySelectorAll('input[name=\'enrollment_ids[]\']:not(:disabled)').forEach(c => c.checked = this.checked)">
                    <label for="select-all" class="text-sm font-medium text-gray-700">Seleccionar todos</label>
                </div>

                <table class="min-w-full divide-y divide-ugarte-border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-10 px-4 py-3"></th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Alumno</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Programa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Curso</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Sección Origen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Sección Destino</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado Origen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Observación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ugarte-border">
                        @foreach($eligible as $item)
                            <tr class="{{ $item->canEnroll ? 'hover:bg-gray-50' : 'bg-gray-50/50 opacity-60' }}">
                                <td class="px-4 py-3 text-center">
                                    @if($item->canEnroll)
                                        <input type="checkbox" name="enrollment_ids[]"
                                               value="{{ $item->enrollment->id }}"
                                               checked
                                               class="rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary">
                                    @else
                                        <input type="checkbox" disabled class="rounded border-gray-200">
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->alumno->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->alumno->email }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $item->program->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $item->course->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 font-mono">Sec. {{ $item->sourceSection->section_code }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($item->targetSection)
                                        <span class="font-mono text-green-700">Sec. {{ $item->targetSection->section_code }}</span>
                                    @else
                                        <span class="text-red-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $item->enrollment->status->badgeClass() }}">
                                        {{ $item->enrollment->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    @if($item->observation)
                                        <span class="text-amber-600 flex items-center gap-1">
                                            <x-erp.icon name="exclamation-triangle" class="h-3.5 w-3.5" />
                                            {{ $item->observation }}
                                        </span>
                                    @else
                                        <span class="text-green-600 flex items-center gap-1">
                                            <x-erp.icon name="check-circle" class="h-3.5 w-3.5" />
                                            Listo para re-matricular
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @error('enrollment_ids')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror

            <div class="mt-4 flex justify-end gap-3">
                <a href="{{ route('admin.re-enrollment.index') }}"
                   class="rounded-lg border border-ugarte-border bg-white px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
                    Confirmar Re-matrícula
                </button>
            </div>
        </form>
    @endif
</x-layouts.app>
