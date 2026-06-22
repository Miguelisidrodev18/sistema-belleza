<x-layouts.app title="Editar Período Académico">
    <x-slot:actions>
        <a href="{{ route('admin.academic-periods.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Volver
        </a>
    </x-slot:actions>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.academic-periods.update', $period) }}">
                @csrf @method('PUT')
                @include('admin.academic-periods._form', ['period' => $period, 'previousOptions' => $previousOptions])
            </form>
        </div>
    </div>
</x-layouts.app>
