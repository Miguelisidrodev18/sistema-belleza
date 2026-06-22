<x-layouts.app title="Matrículas">
    <x-slot:actions>
        <a href="{{ route('admin.enrollments.create') }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
            <x-erp.icon name="plus" class="h-4 w-4" />
            Nueva Matrícula
        </a>
        <a href="{{ route('admin.re-enrollment.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="folder-open" class="h-4 w-4" />
            Re-matrícula
        </a>
    </x-slot:actions>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
        <select name="period_id"
            class="rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
            <option value="">Todos los períodos</option>
            @foreach($periods as $period)
                <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                    {{ $period->name }}{{ $period->is_current ? ' (actual)' : '' }}
                </option>
            @endforeach
        </select>
        <select name="status"
            class="rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
            <option value="">Todos los estados</option>
            @foreach(\App\Enums\EnrollmentStatus::cases() as $s)
                <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
            @endforeach
        </select>
        <input type="text" name="search" placeholder="Buscar alumno o N° matrícula…"
               value="{{ request('search') }}"
               class="rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
        <button type="submit"
            class="rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            Filtrar
        </button>
        @if(request()->hasAny(['period_id','status','search']))
            <a href="{{ route('admin.enrollments.index') }}" class="text-sm text-gray-400 hover:text-gray-600">Limpiar</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-ugarte-border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">N° Matrícula</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Alumno</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Curso / Sección</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Período</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Fecha</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ugarte-border">
                @forelse($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <span class="font-mono text-sm font-semibold text-ugarte-primary">{{ $enrollment->enrollment_number }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm text-gray-900">{{ $enrollment->alumno->name }}</div>
                            <div class="text-xs text-gray-500">{{ $enrollment->alumno->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">{{ $enrollment->courseSection->course->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $enrollment->courseSection->course->program->name }} — Sec. {{ $enrollment->courseSection->section_code }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $enrollment->academicPeriod->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $enrollment->status->badgeClass() }}">
                                {{ $enrollment->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $enrollment->enrolled_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.enrollments.edit', $enrollment) }}"
                                   class="rounded p-1 text-gray-400 hover:text-ugarte-primary hover:bg-ugarte-primary/10 transition-colors"
                                   title="Editar">
                                    <x-erp.icon name="pencil-square" class="h-4 w-4" />
                                </a>
                                @if($enrollment->status->value === 'activa')
                                <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}"
                                      onsubmit="return confirm('¿Retirar esta matrícula?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="rounded p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                        title="Retirar">
                                        <x-erp.icon name="x-circle" class="h-4 w-4" />
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-400">
                            No hay matrículas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($enrollments->hasPages())
        <div class="mt-4">{{ $enrollments->links() }}</div>
    @endif
</x-layouts.app>
