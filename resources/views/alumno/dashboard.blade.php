<x-layouts.app title="Dashboard">
    {{-- Próxima clase --}}
    <x-erp.proxima-clase />

    <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">
            Hola, {{ explode(' ', auth()->user()->name)[0] }}
        </h2>
        <p class="mt-2 text-sm text-gray-400">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</p>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('alumno.enrollments.index') }}"
               class="rounded-lg border border-ugarte-border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Mis Matrículas
            </a>
            <a href="{{ route('alumno.calendar.index') }}"
               class="rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                Ver Calendario
            </a>
        </div>
    </div>
</x-layouts.app>
