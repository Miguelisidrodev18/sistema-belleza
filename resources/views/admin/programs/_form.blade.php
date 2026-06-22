@props(['program' => null])

@php $isEdit = $program !== null; @endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    {{-- Nombre --}}
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Nombre del programa <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name"
            value="{{ old('name', $program?->name) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('name') border-red-500 @enderror">
        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Descripción corta --}}
    <div class="md:col-span-2">
        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción corta</label>
        <input type="text" id="short_description" name="short_description"
            value="{{ old('short_description', $program?->short_description) }}"
            placeholder="Frase de presentación del programa"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
    </div>

    {{-- Descripción larga --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción detallada</label>
        <textarea id="description" name="description" rows="3"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">{{ old('description', $program?->description) }}</textarea>
    </div>

    {{-- Color + preview --}}
    <div x-data="{ color: '{{ old('color', $program?->color ?? '#1D1D1B') }}' }">
        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
            Color de marca <span class="text-red-500">*</span>
        </label>
        <div class="flex items-center gap-3">
            <input type="color" id="color_picker"
                x-model="color"
                @input="$refs.colorText.value = color"
                class="h-10 w-14 cursor-pointer rounded border border-ugarte-border p-0.5">
            <input type="text" id="color" name="color" x-ref="colorText"
                x-model="color"
                @input="color = $event.target.value"
                placeholder="#7B3FA0"
                class="flex-1 rounded-lg border border-ugarte-border px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('color') border-red-500 @enderror">
        </div>
        <div class="mt-2 flex items-center gap-2">
            <div class="h-5 w-5 rounded-full shadow" :style="'background-color:' + color"></div>
            <span class="text-xs text-gray-400">Vista previa del color</span>
        </div>
        @error('color')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Icono --}}
    <div>
        <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icono (nombre)</label>
        <input type="text" id="icon" name="icon"
            value="{{ old('icon', $program?->icon) }}"
            placeholder="Ej: scissors, star"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
    </div>

    {{-- Duración meses --}}
    <div>
        <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-1">
            Duración (meses) <span class="text-red-500">*</span>
        </label>
        <input type="number" id="duration_months" name="duration_months" min="1" max="120"
            value="{{ old('duration_months', $program?->duration_months) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('duration_months') border-red-500 @enderror">
        @error('duration_months')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Total horas --}}
    <div>
        <label for="total_hours" class="block text-sm font-medium text-gray-700 mb-1">
            Total de horas <span class="text-red-500">*</span>
        </label>
        <input type="number" id="total_hours" name="total_hours" min="1"
            value="{{ old('total_hours', $program?->total_hours) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('total_hours') border-red-500 @enderror">
        @error('total_hours')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Nombre del certificado --}}
    <div class="md:col-span-2">
        <label for="certificate_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre en el certificado</label>
        <input type="text" id="certificate_name" name="certificate_name"
            value="{{ old('certificate_name', $program?->certificate_name) }}"
            placeholder="Ej: Especialista en Sistema Integral de Uñas"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
        <p class="mt-1 text-xs text-gray-400">Si está vacío, se usará el nombre del programa.</p>
    </div>

    {{-- Orden --}}
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Orden de visualización</label>
        <input type="number" id="sort_order" name="sort_order" min="0"
            value="{{ old('sort_order', $program?->sort_order ?? 0) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
    </div>

    {{-- Activo --}}
    <div class="flex items-center gap-3 pt-6">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $program?->is_active ?? true) ? 'checked' : '' }}
            class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary">
        <label for="is_active" class="text-sm font-medium text-gray-700">Programa activo</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
        {{ $isEdit ? 'Actualizar Programa' : 'Crear Programa' }}
    </button>
    <a href="{{ route('admin.programs.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
</div>
