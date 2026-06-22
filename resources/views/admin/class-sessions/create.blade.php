<x-layouts.app title="Nueva Sesión Manual">
    <div class="mx-auto max-w-2xl rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-semibold text-gray-900">Nueva Sesión Manual</h2>

        <form action="{{ route('admin.class-sessions.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sección</label>
                <select name="course_section_id" required
                        class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
                    <option value="">Seleccionar sección…</option>
                    @foreach($sections->groupBy('course.program.name') as $program => $secs)
                        <optgroup label="{{ $program }}">
                            @foreach($secs as $sec)
                                <option value="{{ $sec->id }}" @selected(old('course_section_id') == $sec->id)>
                                    {{ $sec->course->name }} — Secc. {{ $sec->section_code }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título (opcional)</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Clase #1: Introducción…"
                       class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Inicio</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required
                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fin</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" required
                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aula</label>
                    <input type="text" name="room" value="{{ old('room') }}"
                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
                    <select name="modality"
                            class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
                        <option value="">Del horario</option>
                        @foreach($modalities as $m)
                            <option value="{{ $m->value }}" @selected(old('modality') === $m->value)>{{ $m->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Enlace Zoom/Meet (opcional)</label>
                <input type="url" name="meeting_url" value="{{ old('meeting_url') }}" placeholder="https://zoom.us/j/..."
                       class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                    Crear sesión
                </button>
                <a href="{{ route('admin.class-sessions.index') }}"
                   class="rounded-lg border border-ugarte-border px-5 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
