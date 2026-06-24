<x-layouts.app title="Mis Matrículas">
    @if($enrollments->isEmpty())
        <div class="rounded-xl border border-ugarte-border bg-white p-10 text-center shadow-sm">
            <x-erp.icon name="academic-cap" class="mx-auto mb-3 h-10 w-10 text-gray-300" />
            <p class="text-sm text-gray-500">No tienes matrículas en el período actual.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($enrollments as $enrollment)
                @php $teacher = $enrollment->courseSection->primaryTeacher(); @endphp
                <a href="{{ route('alumno.sections.show', $enrollment->courseSection) }}"
                   class="group block rounded-xl border border-ugarte-border bg-white p-5 shadow-sm transition-all hover:border-ugarte-primary/30 hover:shadow-md">
                    <div class="mb-1 flex items-start justify-between gap-2">
                        <span class="font-mono text-xs font-semibold text-ugarte-primary">{{ $enrollment->enrollment_number }}</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $enrollment->status->badgeClass() }}">
                            {{ $enrollment->status->label() }}
                        </span>
                    </div>

                    <h3 class="mt-2 text-sm font-semibold text-gray-900 group-hover:text-ugarte-primary transition-colors">{{ $enrollment->courseSection->course->name }}</h3>
                    <p class="mt-0.5 text-xs text-gray-500">{{ $enrollment->courseSection->course->program->name }}</p>

                    <div class="mt-3 space-y-1 text-xs text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <x-erp.icon name="academic-cap" class="h-3.5 w-3.5 text-gray-400" />
                            <span>Sección {{ $enrollment->courseSection->section_code }}</span>
                        </div>
                        @if($teacher)
                            <div class="flex items-center gap-1.5">
                                <x-erp.icon name="user" class="h-3.5 w-3.5 text-gray-400" />
                                <span>{{ $teacher->name }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-1.5">
                            <x-erp.icon name="calendar-days" class="h-3.5 w-3.5 text-gray-400" />
                            <span>Desde {{ $enrollment->enrolled_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end text-xs font-medium text-ugarte-primary opacity-0 transition-opacity group-hover:opacity-100">
                        Ver sección
                        <x-erp.icon name="chevron-right" class="ml-1 h-3.5 w-3.5" />
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-layouts.app>
