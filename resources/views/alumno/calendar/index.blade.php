<x-layouts.app title="Mi Calendario">
    {{-- Próxima clase --}}
    <x-erp.proxima-clase />

    {{-- Calendario --}}
    <x-erp.calendar :fetch-url="route('alumno.calendar.sessions')" />
</x-layouts.app>
