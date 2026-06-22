<x-layouts.app title="Editar Curso">
    <x-slot:actions>
        <a href="{{ route('admin.programs.show', $program) }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Volver a {{ $program->name }}
        </a>
    </x-slot:actions>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.programs.courses.update', [$program, $course]) }}">
                @csrf @method('PUT')
                @include('admin.programs.courses._form', ['program' => $program, 'course' => $course])
            </form>
        </div>
    </div>
</x-layouts.app>
