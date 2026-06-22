<x-layouts.app title="Materiales — {{ $courseSection->section_code }}">
    <x-slot:actions>
        <a href="{{ route('alumno.enrollments.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Mis Matrículas
        </a>
    </x-slot:actions>

    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-900">Materiales</h1>
        <p class="text-sm text-gray-500">{{ $courseSection->course->name }} · {{ $courseSection->section_code }}</p>
    </div>

    <x-erp.material-list :materials="$materials" />
</x-layouts.app>
