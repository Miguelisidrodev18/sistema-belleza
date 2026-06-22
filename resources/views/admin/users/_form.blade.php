@props(['user' => null])

@php $isEdit = $user !== null; @endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-5" x-data="{
    dniLoading: false,
    dniError: '',
    fetchDni() {
        const num = this.$refs.dniInput.value.trim();
        if (num.length !== 8 || !/^\d{8}$/.test(num)) {
            this.dniError = 'Ingresa un DNI válido de 8 dígitos.';
            return;
        }
        this.dniLoading = true;
        this.dniError = '';
        fetch('/admin/api/dni-lookup?numero=' + num)
            .then(r => r.ok ? r.json() : r.json().then(d => Promise.reject(d.error || 'No encontrado')))
            .then(d => { this.$refs.nameInput.value = d.fullName; })
            .catch(e => { this.dniError = typeof e === 'string' ? e : 'No se pudo consultar el DNI.'; })
            .finally(() => { this.dniLoading = false; });
    }
}">
    {{-- Left column: Personal data --}}
    <div class="lg:col-span-3 space-y-6">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Datos Personales</h3>

            <div class="space-y-4">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nombre completo <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" x-ref="nameInput" value="{{ old('name', $user?->name) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Correo electrónico <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user?->email) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="dni" class="mb-1 block text-sm font-medium text-gray-700">DNI</label>
                        <div class="flex gap-2">
                            <input type="text" name="dni" id="dni" x-ref="dniInput" value="{{ old('dni', $user?->dni) }}"
                                maxlength="8" inputmode="numeric" pattern="\d{8}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary"
                                @keydown.enter.prevent="fetchDni()">
                            @if(!$isEdit)
                            <button type="button" @click="fetchDni()"
                                class="flex shrink-0 items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-50"
                                :disabled="dniLoading">
                                <template x-if="dniLoading">
                                    <svg class="h-3.5 w-3.5 animate-spin text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </template>
                                <template x-if="!dniLoading">
                                    <x-erp.icon name="magnifying-glass" class="h-3.5 w-3.5" />
                                </template>
                                Buscar
                            </button>
                            @endif
                        </div>
                        <p x-show="dniError" x-text="dniError" class="mt-1 text-xs text-red-600"></p>
                        @error('dni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="mb-1 block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user?->phone) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="birth_date" class="mb-1 block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user?->birth_date?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                        @error('birth_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="gender" class="mb-1 block text-sm font-medium text-gray-700">Género</label>
                        <select name="gender" id="gender"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                            <option value="">Seleccionar</option>
                            <option value="M" {{ old('gender', $user?->gender) === 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('gender', $user?->gender) === 'F' ? 'selected' : '' }}>Femenino</option>
                            <option value="otro" {{ old('gender', $user?->gender) === 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('gender') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="mb-1 block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $user?->address) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Right column: Role, password, photo --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Acceso</h3>

            <div class="space-y-4">
                <div>
                    <label for="role" class="mb-1 block text-sm font-medium text-gray-700">Rol <span class="text-red-500">*</span></label>
                    <select name="role" id="role" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                        <option value="alumno" {{ old('role', $user?->role) === 'alumno' ? 'selected' : '' }}>Alumno</option>
                        <option value="docente" {{ old('role', $user?->role) === 'docente' ? 'selected' : '' }}>Docente</option>
                        <option value="administrador" {{ old('role', $user?->role) === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">
                        Contraseña
                        @if(!$isEdit)
                            <span class="text-gray-400 text-xs">(opcional si ingresaste DNI)</span>
                        @else
                            <span class="text-gray-400 text-xs">(dejar vacío para mantener)</span>
                        @endif
                    </label>
                    <input type="password" name="password" id="password"
                        @if(!$isEdit) placeholder="Dejar vacío para usar el DNI como contraseña" @endif
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    @if(!$isEdit)
                        <p class="mt-1 text-xs text-gray-500">Si no ingresa contraseña, el DNI del usuario será su contraseña inicial y deberá cambiarla al primer inicio de sesión.</p>
                    @endif
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary">
                    <label for="is_active" class="text-sm text-gray-700">Usuario activo</label>
                </div>

                @if($isEdit)
                <div class="flex items-center gap-2">
                    <input type="hidden" name="must_change_password" value="0">
                    <input type="checkbox" name="must_change_password" id="must_change_password" value="1"
                        {{ old('must_change_password', $user?->must_change_password) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                    <label for="must_change_password" class="text-sm text-gray-700">
                        Forzar cambio de contraseña en próximo login
                    </label>
                </div>
                @endif
            </div>
        </div>

        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Foto de perfil</h3>
            <div x-data="{ preview: '{{ $user?->photo ? Storage::url($user->photo) : '' }}' }">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full bg-ugarte-primary/10">
                        <template x-if="preview">
                            <img :src="preview" class="h-full w-full object-cover" alt="Preview">
                        </template>
                        <template x-if="!preview">
                            <span class="text-lg font-semibold text-ugarte-primary">{{ $user?->initials ?? '?' }}</span>
                        </template>
                    </div>
                    <div>
                        <label class="cursor-pointer rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Seleccionar foto
                            <input type="file" name="photo" accept="image/*" class="hidden"
                                @change="preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG. Máx. 2 MB.</p>
                    </div>
                </div>
            </div>
            @error('photo') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <x-ui.button variant="outline" size="sm" href="{{ route('admin.users.index') }}">Cancelar</x-ui.button>
    <x-ui.button variant="primary" size="sm" type="submit">
        {{ $isEdit ? 'Guardar Cambios' : 'Crear Usuario' }}
    </x-ui.button>
</div>
