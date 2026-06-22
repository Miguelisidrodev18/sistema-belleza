<x-layouts.app title="Editar Sección Académica">
    <x-slot:actions>
        <a href="{{ route('admin.course-sections.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Volver
        </a>
    </x-slot:actions>

    <div class="max-w-3xl">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.course-sections.update', $courseSection) }}">
                @csrf @method('PUT')
                @include('admin.course-sections._form', [
                    'courseSection'  => $courseSection,
                    'courses'        => $courses,
                    'periods'        => $periods,
                    'teachers'       => $teachers,
                    'primaryTeacher' => $primaryTeacher,
                ])
            </form>
        </div>
    </div>
</x-layouts.app>
