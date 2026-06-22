<x-layouts.app title="Editar Matrícula">
    <x-slot:actions>
        <a href="{{ route('admin.enrollments.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Volver
        </a>
    </x-slot:actions>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Formulario principal --}}
        <div class="lg:col-span-2 rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h2 class="mb-1 text-lg font-semibold text-gray-900">
                Matrícula <span class="font-mono text-ugarte-primary">{{ $enrollment->enrollment_number }}</span>
            </h2>
            <p class="mb-6 text-sm text-gray-500">
                {{ $enrollment->alumno->name }} — {{ $enrollment->courseSection->course->name }}
                ({{ $enrollment->academicPeriod->name }})
            </p>

            <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}">
                @csrf @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                        <select name="status" required
                            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
                            @foreach(\App\Enums\EnrollmentStatus::cases() as $s)
                                <option value="{{ $s->value }}" {{ $enrollment->status->value === $s->value ? 'selected' : '' }}>
                                    {{ $s->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea name="remarks" rows="3"
                            class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">{{ old('remarks', $enrollment->remarks) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.enrollments.index') }}"
                           class="rounded-lg border border-ugarte-border bg-white px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Historial de actividad --}}
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-gray-900">Historial de Actividad</h3>
            @forelse($enrollment->activities->sortByDesc('created_at') as $activity)
                <div class="mb-3 border-l-2 border-ugarte-primary/30 pl-3">
                    <div class="text-xs font-medium text-gray-700">
                        @if($activity->action === 'enrolled') Matriculado
                        @elseif($activity->action === 'withdrawn') Retirado
                        @elseif($activity->action === 're_enrolled') Re-matriculado
                        @else Cambio de estado
                        @endif
                    </div>
                    @if($activity->from_status || $activity->to_status)
                        <div class="text-xs text-gray-500">
                            {{ $activity->from_status ? \App\Enums\EnrollmentStatus::from($activity->from_status)->label() : '—' }}
                            → {{ $activity->to_status ? \App\Enums\EnrollmentStatus::from($activity->to_status)->label() : '—' }}
                        </div>
                    @endif
                    <div class="text-xs text-gray-400">
                        {{ $activity->performedBy?->name ?? 'Sistema' }} · {{ $activity->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400">Sin actividad registrada.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
