@php $s = $schedule ?? null; @endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Día de la semana</label>
    <select name="day_of_week" required
            class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary @error('day_of_week') border-red-400 @enderror">
        @foreach($days as $num => $name)
            <option value="{{ $num }}" @selected(old('day_of_week', $s?->day_of_week) == $num)>{{ $name }}</option>
        @endforeach
    </select>
    @error('day_of_week') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Hora inicio</label>
        <input type="time" name="start_time" value="{{ old('start_time', $s?->start_time) }}" required
               class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
        @error('start_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Hora fin</label>
        <input type="time" name="end_time" value="{{ old('end_time', $s?->end_time) }}" required
               class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
        @error('end_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Aula (opcional)</label>
    <input type="text" name="room" value="{{ old('room', $s?->room) }}" placeholder="Ej: Aula 3, Lab. Belleza"
           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
    <select name="modality" required
            class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
        @foreach($modalities as $m)
            <option value="{{ $m->value }}" @selected(old('modality', $s?->modality?->value) === $m->value)>{{ $m->label() }}</option>
        @endforeach
    </select>
</div>

@if($editing ?? false)
<div class="flex items-center gap-2">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $s?->is_active ?? true))
           class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary">
    <label for="is_active" class="text-sm text-gray-700">Horario activo</label>
</div>
@endif

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Notas (opcional)</label>
    <textarea name="notes" rows="2" placeholder="Observaciones sobre este horario..."
              class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">{{ old('notes', $s?->notes) }}</textarea>
</div>
