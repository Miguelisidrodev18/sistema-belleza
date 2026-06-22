<x-layouts.app title="Re-matrícula">
    <x-slot:actions>
        <a href="{{ route('admin.enrollments.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Matrículas
        </a>
    </x-slot:actions>

    <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <h2 class="mb-2 text-lg font-semibold text-gray-900">Asistente de Re-matrícula</h2>
        <p class="mb-6 text-sm text-gray-500">
            Selecciona el período de origen y el período de destino para ver los alumnos elegibles.
            Solo se muestran alumnos con matrícula activa o completada que no estén ya inscritos en el período destino.
        </p>

        <form method="POST" action="{{ route('admin.re-enrollment.preview') }}" class="flex flex-wrap items-end gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Período de Origen</label>
                <select name="source_period_id" required
                    class="rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
                    <option value="">Seleccionar…</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ $previousPeriod?->id == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}
                        </option>
                    @endforeach
                </select>
                @error('source_period_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Período de Destino</label>
                <select name="target_period_id" required
                    class="rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
                    <option value="">Seleccionar…</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ $currentPeriod?->id == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}{{ $period->is_current ? ' (actual)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('target_period_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
                Ver Alumnos Elegibles
            </button>
        </form>
    </div>
</x-layouts.app>
