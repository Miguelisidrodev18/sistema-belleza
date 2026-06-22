<x-layouts.app title="Editar Sesión #{{ $classSession->session_number }}">
    <div class="mx-auto max-w-2xl space-y-5">
        {{-- Sesión --}}
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-900">
                Sesión #{{ $classSession->session_number }} —
                {{ $classSession->courseSection->course->name }}
            </h2>

            <form action="{{ route('admin.class-sessions.update', $classSession) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="title" value="{{ old('title', $classSession->title) }}"
                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Inicio</label>
                        <input type="datetime-local" name="starts_at" required
                               value="{{ old('starts_at', $classSession->starts_at->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fin</label>
                        <input type="datetime-local" name="ends_at" required
                               value="{{ old('ends_at', $classSession->ends_at->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Aula</label>
                        <input type="text" name="room" value="{{ old('room', $classSession->room) }}"
                               class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="status" required
                                class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
                            @foreach($statuses as $st)
                                <option value="{{ $st->value }}" @selected(old('status', $classSession->status->value) === $st->value)>
                                    {{ $st->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Razón de cancelación (si aplica)</label>
                    <input type="text" name="cancelled_reason" value="{{ old('cancelled_reason', $classSession->cancelled_reason) }}"
                           class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                    <textarea name="notes" rows="2"
                              class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">{{ old('notes', $classSession->notes) }}</textarea>
                </div>

                {{-- Meeting --}}
                <fieldset class="rounded-lg border border-ugarte-border p-4">
                    <legend class="px-2 text-sm font-semibold text-gray-700">Configuración de videollamada</legend>

                    <div class="space-y-3 mt-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plataforma</label>
                                <select name="meeting_platform"
                                        class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
                                    @foreach($platforms as $p)
                                        <option value="{{ $p->value }}" @selected(old('meeting_platform', $classSession->meeting?->platform?->value) === $p->value)>
                                            {{ $p->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado meeting</label>
                                <select name="meeting_status"
                                        class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary">
                                    @foreach(['pending' => 'Pendiente', 'live' => 'En vivo', 'ended' => 'Finalizado', 'cancelled' => 'Cancelado'] as $val => $label)
                                        <option value="{{ $val }}" @selected(old('meeting_status', $classSession->meeting?->status) === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL de la reunión</label>
                            <input type="url" name="meeting_url" value="{{ old('meeting_url', $classSession->meeting?->meeting_url) }}"
                                   placeholder="https://zoom.us/j/..."
                                   class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meeting ID</label>
                                <input type="text" name="meeting_id" value="{{ old('meeting_id', $classSession->meeting?->meeting_id) }}"
                                       class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                                <input type="text" name="passcode" value="{{ old('passcode', $classSession->meeting?->passcode) }}"
                                       class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL de grabación</label>
                            <input type="url" name="recording_url" value="{{ old('recording_url', $classSession->meeting?->recording_url) }}"
                                   placeholder="https://..."
                                   class="w-full rounded-lg border-ugarte-border px-3 py-2 text-sm focus:ring-2 focus:ring-ugarte-primary" />
                        </div>
                    </div>
                </fieldset>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-medium text-white hover:bg-ugarte-dark">
                        Guardar cambios
                    </button>
                    <a href="{{ route('admin.class-sessions.index') }}"
                       class="rounded-lg border border-ugarte-border px-5 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
