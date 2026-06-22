<x-layouts.app title="Nuevo Horario — {{ $courseSection->course->name }}">
    <div class="mx-auto max-w-xl rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-semibold text-gray-900">Nuevo Horario — {{ $courseSection->course->name }}</h2>

        <form action="{{ route('admin.course-sections.schedules.store', $courseSection) }}" method="POST" class="space-y-4">
            @csrf
            @include('admin.course-sections.schedules._form')
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                    Guardar horario
                </button>
                <a href="{{ route('admin.course-sections.schedules.index', $courseSection) }}"
                   class="rounded-lg border border-ugarte-border px-5 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
