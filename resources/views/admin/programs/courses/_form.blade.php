@props(['course' => null, 'program'])

@php $isEdit = $course !== null; @endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    {{-- Nombre --}}
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Nombre del curso <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name"
            value="{{ old('name', $course?->name) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('name') border-red-500 @enderror">
        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Código --}}
    <div>
        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Código</label>
        <input type="text" id="code" name="code"
            value="{{ old('code', $course?->code) }}"
            placeholder="Ej: BAR101"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
    </div>

    {{-- Horas --}}
    <div>
        <label for="hours" class="block text-sm font-medium text-gray-700 mb-1">
            Horas <span class="text-red-500">*</span>
        </label>
        <input type="number" id="hours" name="hours" min="1"
            value="{{ old('hours', $course?->hours) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('hours') border-red-500 @enderror">
        @error('hours')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Descripción --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
        <textarea id="description" name="description" rows="3"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">{{ old('description', $course?->description) }}</textarea>
    </div>

    {{-- Orden --}}
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
        <input type="number" id="sort_order" name="sort_order" min="0"
            value="{{ old('sort_order', $course?->sort_order ?? 0) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
    </div>

    {{-- Activo --}}
    <div class="flex items-center gap-3 pt-6">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $course?->is_active ?? true) ? 'checked' : '' }}
            class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary">
        <label for="is_active" class="text-sm font-medium text-gray-700">Curso activo</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
        {{ $isEdit ? 'Actualizar Curso' : 'Crear Curso' }}
    </button>
    <a href="{{ route('admin.programs.show', $program) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
</div>
