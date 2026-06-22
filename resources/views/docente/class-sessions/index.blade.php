<x-layouts.app title="Mis Clases">
    {{-- HOY --}}
    @if($today->isNotEmpty())
    <div class="mb-6">
        <h2 class="mb-3 text-base font-semibold text-gray-700">Hoy — {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach($today as $session)
            <div class="rounded-xl border-2 {{ $session->status->value === 'in_progress' ? 'border-yellow-400' : 'border-ugarte-border' }} bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $session->title ?? "Clase #{$session->session_number}" }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $session->courseSection->course->name }}</p>
                        <p class="text-sm text-gray-400">{{ $session->starts_at->format('H:i') }} – {{ $session->ends_at->format('H:i') }}</p>
                    </div>
                    <span class="text-xs rounded-full px-2.5 py-0.5 font-medium {{ $session->status->badgeClass() }}">
                        {{ $session->status->label() }}
                    </span>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="{{ route('docente.class-sessions.show', $session) }}"
                       class="rounded-lg bg-ugarte-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-ugarte-dark">
                        Tomar asistencia
                    </a>
                    @if($session->meeting?->meeting_url)
                    <a href="{{ $session->meeting->meeting_url }}" target="_blank"
                       class="rounded-lg border border-ugarte-border px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                        Abrir {{ $session->meeting->platform->label() }}
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- PRÓXIMAS --}}
    @if($upcoming->isNotEmpty())
    <div class="mb-6">
        <h2 class="mb-3 text-base font-semibold text-gray-700">Próximas clases</h2>
        <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
            <table class="min-w-full divide-y divide-ugarte-border text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Clase</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Hora</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Estado</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ugarte-border">
                    @foreach($upcoming as $session)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-semibold text-ugarte-primary">#{{ $session->session_number }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $session->title ?? "Clase #{$session->session_number}" }}
                            <br><span class="text-xs text-gray-400">{{ $session->courseSection->course->name }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $session->starts_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $session->starts_at->format('H:i') }} – {{ $session->ends_at->format('H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $session->status->badgeClass() }}">{{ $session->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('docente.class-sessions.show', $session) }}" class="text-xs text-ugarte-primary hover:underline">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- PASADAS --}}
    @if($past->isNotEmpty())
    <div>
        <h2 class="mb-3 text-base font-semibold text-gray-700">Clases recientes</h2>
        <div class="overflow-hidden rounded-xl border border-ugarte-border bg-white shadow-sm">
            <table class="min-w-full divide-y divide-ugarte-border text-sm">
                <tbody class="divide-y divide-ugarte-border">
                    @foreach($past as $session)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-semibold text-gray-400">#{{ $session->session_number }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $session->title ?? "Clase #{$session->session_number}" }}
                            <br><span class="text-xs text-gray-400">{{ $session->courseSection->course->name }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $session->starts_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $session->status->badgeClass() }}">{{ $session->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('docente.class-sessions.show', $session) }}" class="text-xs text-ugarte-primary hover:underline">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($today->isEmpty() && $upcoming->isEmpty() && $past->isEmpty())
    <div class="rounded-xl border border-ugarte-border bg-white p-10 text-center text-sm text-gray-400 shadow-sm">
        No tienes sesiones de clase aún.
    </div>
    @endif
</x-layouts.app>
