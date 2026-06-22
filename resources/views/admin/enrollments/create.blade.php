<x-layouts.app title="Nueva Matrícula">
    <x-slot:actions>
        <a href="{{ route('admin.enrollments.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Volver
        </a>
    </x-slot:actions>

    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-lg font-semibold text-gray-900">Registrar Matrícula</h2>

        <form method="POST" action="{{ route('admin.enrollments.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alumno <span class="text-red-500">*</span></label>
                <select name="alumno_id" required
                    class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('alumno_id') border-red-400 @enderror">
                    <option value="">Seleccionar alumno…</option>
                    @foreach($alumnos as $alumno)
                        <option value="{{ $alumno->id }}" {{ old('alumno_id') == $alumno->id ? 'selected' : '' }}>
                            {{ $alumno->name }} — {{ $alumno->email }}
                        </option>
                    @endforeach
                </select>
                @error('alumno_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sección <span class="text-red-500">*</span></label>
                <select name="course_section_id" required
                    class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('course_section_id') border-red-400 @enderror">
                    <option value="">Seleccionar sección…</option>
                    @foreach($sections as $programName => $programSections)
                        <optgroup label="{{ $programName }}">
                            @foreach($programSections as $section)
                                @php
                                    $cap = $capacities[$section->id] ?? ['enrolled' => 0, 'capacity' => 0, 'available' => 0];
                                    $full = $cap['available'] <= 0;
                                @endphp
                                <option value="{{ $section->id }}"
                                    {{ old('course_section_id') == $section->id ? 'selected' : '' }}
                                    {{ $full ? 'disabled' : '' }}>
                                    {{ $section->course->name }} — Sec. {{ $section->section_code }}
                                    ({{ $cap['enrolled'] }}/{{ $cap['capacity'] }} matriculados
                                    {{ $full ? '— LLENA' : "— {$cap['available']} vacantes" }})
                                    — {{ $section->academicPeriod->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('course_section_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea name="remarks" rows="3"
                    class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary"
                    placeholder="Información adicional sobre esta matrícula…">{{ old('remarks') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.enrollments.index') }}"
                   class="rounded-lg border border-ugarte-border bg-white px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
                    Registrar Matrícula
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
