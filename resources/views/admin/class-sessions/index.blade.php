<x-layouts.app title="Sesiones de Clase">
    <x-slot:actions>
        <a href="{{ route('admin.session-generator.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
            <x-erp.icon name="calendar-days" class="h-4 w-4" />
            Generar Sesiones
        </a>
        <a href="{{ route('admin.class-sessions.create') }}"
           class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
            <x-erp.icon name="plus" class="h-4 w-4" />
            Sesión Manual
        </a>
    </x-slot:actions>

    {{-- Filtros --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-3">
        <select name="section" onchange="this.form.submit()"
                class="rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
            <option value="">Todas las secciones</option>
            @foreach($sections as $sec)
                <option value="{{ $sec->id }}" @selected(request('section') == $sec->id)>
                    {{ $sec->course->name }} — Secc. {{ $sec->section_code }}
                </option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()"
                class="rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
            <option value="">Todos los estados</option>
            @foreach($statuses as $st)
                <option value="{{ $st->value }}" @selected(request('status') === $st->value)>{{ $st->label() }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
               class="rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
        @if(request()->hasAny(['section','status','date']))
            <a href="{{ route('admin.class-sessions.index') }}" class="rounded-lg border border-ugarte-border px-3 py-2 text-sm text-gray-500 hover:bg-gray-50">Limpiar</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-ugarte-border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Título</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Sección</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Hora</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ugarte-border">
                @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-sm font-semibold text-ugarte-primary">
                            #{{ $session->session_number }}
                            @if($session->is_generated)
                                <span class="ml-1 text-xs text-gray-400">auto</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $session->title ?? "Clase #{$session->session_number}" }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $session->courseSection->course->name }} — Secc. {{ $session->courseSection->section_code }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $session->starts_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $session->starts_at->format('H:i') }} – {{ $session->ends_at->format('H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $session->status->badgeClass() }}">
                                {{ $session->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.class-sessions.edit', $session) }}"
                               class="text-xs text-ugarte-primary hover:underline">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-400">
                            No hay sesiones. <a href="{{ route('admin.session-generator.index') }}" class="text-ugarte-primary hover:underline">Generar sesiones</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-ugarte-border">
            {{ $sessions->links() }}
        </div>
    </div>
</x-layouts.app>
