@props(['courseSection' => null, 'courses', 'periods', 'teachers', 'primaryTeacher' => null])

@php $isEdit = $courseSection !== null; @endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    {{-- Curso --}}
    <div class="md:col-span-2">
        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">
            Curso <span class="text-red-500">*</span>
        </label>
        <select id="course_id" name="course_id"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('course_id') border-red-500 @enderror">
            <option value="">— Seleccionar curso —</option>
            @foreach($courses->groupBy(fn($c) => $c->program->name) as $programName => $programCourses)
                <optgroup label="{{ $programName }}">
                    @foreach($programCourses as $course)
                        <option value="{{ $course->id }}"
                            {{ old('course_id', $courseSection?->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}{{ $course->code ? ' ('.$course->code.')' : '' }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @error('course_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Período académico --}}
    <div>
        <label for="academic_period_id" class="block text-sm font-medium text-gray-700 mb-1">
            Período académico <span class="text-red-500">*</span>
        </label>
        <select id="academic_period_id" name="academic_period_id"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('academic_period_id') border-red-500 @enderror">
            <option value="">— Seleccionar período —</option>
            @foreach($periods as $period)
                <option value="{{ $period->id }}"
                    {{ old('academic_period_id', $courseSection?->academic_period_id) == $period->id ? 'selected' : '' }}>
                    {{ $period->name }}{{ $period->is_current ? ' (actual)' : '' }}
                </option>
            @endforeach
        </select>
        @error('academic_period_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Código de sección --}}
    <div>
        <label for="section_code" class="block text-sm font-medium text-gray-700 mb-1">
            Código de sección <span class="text-red-500">*</span>
        </label>
        <input type="text" id="section_code" name="section_code" maxlength="10"
            value="{{ old('section_code', $courseSection?->section_code ?? 'A') }}"
            placeholder="A, B, M, T..."
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('section_code') border-red-500 @enderror">
        @error('section_code')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Capacidad --}}
    <div>
        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">
            Capacidad <span class="text-red-500">*</span>
        </label>
        <input type="number" id="capacity" name="capacity" min="1" max="500"
            value="{{ old('capacity', $courseSection?->capacity ?? 30) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('capacity') border-red-500 @enderror">
        @error('capacity')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Estado --}}
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
            Estado <span class="text-red-500">*</span>
        </label>
        <select id="status" name="status"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('status') border-red-500 @enderror">
            @foreach(\App\Enums\CourseSectionStatus::cases() as $status)
                <option value="{{ $status->value }}"
                    {{ old('status', $courseSection?->status?->value ?? 'draft') === $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
        @error('status')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Activo --}}
    <div class="flex items-center gap-3 pt-6">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $courseSection?->is_active ?? true) ? 'checked' : '' }}
            class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary">
        <label for="is_active" class="text-sm font-medium text-gray-700">Sección activa</label>
    </div>

    {{-- Docente principal --}}
    <div class="md:col-span-2 border-t border-ugarte-border pt-5">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Docente principal</h4>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Docente</label>
                <select id="teacher_id" name="teacher_id"
                    class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
                    <option value="">— Sin asignar —</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ old('teacher_id', $primaryTeacher?->id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="teacher_role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select id="teacher_role" name="teacher_role"
                    class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
                    <option value="principal">Principal</option>
                    <option value="asistente">Asistente</option>
                    <option value="reemplazo">Reemplazo</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
        {{ $isEdit ? 'Actualizar Sección' : 'Crear Sección' }}
    </button>
    <a href="{{ route('admin.course-sections.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
</div>
