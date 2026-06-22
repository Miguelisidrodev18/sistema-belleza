<x-layouts.app title="Nuevo Programa">
    <x-slot:actions>
        <a href="{{ route('admin.programs.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Volver
        </a>
    </x-slot:actions>

    <div class="max-w-3xl">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.programs.store') }}">
                @csrf
                @include('admin.programs._form')
            </form>
        </div>
    </div>
</x-layouts.app>
