@props(['period' => null, 'previousOptions' => collect()])

@php $isEdit = $period !== null; @endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    {{-- Nombre --}}
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Nombre del período <span class="text-red-500">*</span>
        </label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $period?->name) }}"
            placeholder="Ej: 2026-I, 2025-II"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('name') border-red-500 @enderror"
        >
        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Fecha inicio --}}
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
            Fecha de inicio <span class="text-red-500">*</span>
        </label>
        <input
            type="date"
            id="start_date"
            name="start_date"
            value="{{ old('start_date', $period?->start_date?->format('Y-m-d')) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('start_date') border-red-500 @enderror"
        >
        @error('start_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Fecha fin --}}
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
            Fecha de fin <span class="text-red-500">*</span>
        </label>
        <input
            type="date"
            id="end_date"
            name="end_date"
            value="{{ old('end_date', $period?->end_date?->format('Y-m-d')) }}"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('end_date') border-red-500 @enderror"
        >
        @error('end_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Estado --}}
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
            Estado <span class="text-red-500">*</span>
        </label>
        <select
            id="status"
            name="status"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('status') border-red-500 @enderror"
        >
            @foreach(\App\Enums\AcademicPeriodStatus::cases() as $status)
                <option value="{{ $status->value }}" {{ old('status', $period?->status?->value) === $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
        @error('status')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    {{-- Período anterior --}}
    <div>
        <label for="previous_period_id" class="block text-sm font-medium text-gray-700 mb-1">
            Período anterior
        </label>
        <select
            id="previous_period_id"
            name="previous_period_id"
            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary @error('previous_period_id') border-red-500 @enderror"
        >
            <option value="">— Ninguno —</option>
            @foreach($previousOptions as $option)
                <option
                    value="{{ $option->id }}"
                    {{ old('previous_period_id', $period?->previous_period_id) == $option->id ? 'selected' : '' }}
                >
                    {{ $option->name }}
                </option>
            @endforeach
        </select>
        @error('previous_period_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
        {{ $isEdit ? 'Actualizar Período' : 'Crear Período' }}
    </button>
    <a href="{{ route('admin.academic-periods.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
        Cancelar
    </a>
</div>
