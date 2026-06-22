<x-layouts.app title="Períodos Académicos">
    <x-slot:actions>
        <a href="{{ route('admin.academic-periods.create') }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
            <x-erp.icon name="plus" class="h-4 w-4" />
            Nuevo Período
        </a>
    </x-slot:actions>

    <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-ugarte-border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Período</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Fechas</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Anterior</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ugarte-border bg-white">
                @forelse($periods as $period)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-900">{{ $period->name }}</span>
                                @if($period->is_current)
                                    <span class="inline-flex items-center rounded-full bg-ugarte-primary/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-ugarte-primary">
                                        Actual
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $period->start_date->format('d/m/Y') }} — {{ $period->end_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $period->previousPeriod?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = ['planificacion' => 'yellow', 'activo' => 'green', 'finalizado' => 'gray'];
                                $color = $colors[$period->status->value] ?? 'gray';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $color === 'green' ? 'bg-green-100 text-green-700' : ($color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $period->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                @unless($period->is_current)
                                    <form method="POST" action="{{ route('admin.academic-periods.set-current', $period) }}">
                                        @csrf
                                        <button type="submit"
                                            class="rounded-lg border border-ugarte-primary/30 px-3 py-1.5 text-xs font-medium text-ugarte-primary hover:bg-ugarte-primary/5 transition-colors"
                                            title="Marcar como actual">
                                            Marcar actual
                                        </button>
                                    </form>
                                @endunless
                                <a href="{{ route('admin.academic-periods.edit', $period) }}"
                                   class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
                                   title="Editar">
                                    <x-erp.icon name="pencil" class="h-4 w-4" />
                                </a>
                                @unless($period->is_current)
                                    <form method="POST" action="{{ route('admin.academic-periods.destroy', $period) }}"
                                          onsubmit="return confirm('¿Eliminar el período «{{ $period->name }}»?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                            title="Eliminar">
                                            <x-erp.icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
                            No hay períodos académicos registrados.
                            <a href="{{ route('admin.academic-periods.create') }}" class="text-ugarte-primary hover:underline">Crear el primero</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($periods->hasPages())
        <div class="mt-4">{{ $periods->links() }}</div>
    @endif
</x-layouts.app>
