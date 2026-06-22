<x-layouts.app title="Editar Programa">
    <x-slot:actions>
        <a href="{{ route('admin.programs.show', $program) }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Volver al detalle
        </a>
    </x-slot:actions>

    <div class="max-w-3xl">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.programs.update', $program) }}">
                @csrf @method('PUT')
                @include('admin.programs._form', ['program' => $program])
            </form>
        </div>
    </div>
</x-layouts.app>
