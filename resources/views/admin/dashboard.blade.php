<x-layouts.app title="Dashboard">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Alumnos activos</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\User::alumnos()->active()->count() }}</p>
        </div>
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Docentes activos</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\User::docentes()->active()->count() }}</p>
        </div>
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Administradores</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\User::admins()->active()->count() }}</p>
        </div>
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total usuarios</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
        </div>
    </div>

    @if($currentPeriod)
    <div class="mt-6 flex items-center gap-3 rounded-xl border border-ugarte-primary/20 bg-ugarte-primary/5 px-5 py-3">
        <x-erp.icon name="calendar-days" class="h-5 w-5 shrink-0 text-ugarte-primary" />
        <div>
            <span class="text-sm font-semibold text-ugarte-primary">Período actual: {{ $currentPeriod->name }}</span>
            <span class="ml-2 text-xs text-gray-500">
                {{ $currentPeriod->start_date->format('d/m/Y') }} — {{ $currentPeriod->end_date->format('d/m/Y') }}
            </span>
        </div>
    </div>
    @endif

    <div class="mt-6 rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Bienvenido, {{ auth()->user()->name }}</h2>
        <p class="mt-2 text-sm text-gray-500">
            Este es el panel de administración del ERP Académico Ugarte. Las demás secciones se habilitarán conforme se implementen los módulos.
        </p>
    </div>
</x-layouts.app>
