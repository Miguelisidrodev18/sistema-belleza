<x-layouts.app title="Mi Perfil">
    <div class="max-w-2xl space-y-6">
        {{-- Profile info --}}
        <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full bg-ugarte-primary/10">
                    @if($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" class="h-full w-full object-cover" alt="{{ $user->name }}">
                    @else
                        <span class="text-xl font-bold text-ugarte-primary">{{ $user->initials }}</span>
                    @endif
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->role_label }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- Edit info --}}
        <form method="POST" action="{{ route('docente.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Editar Información</h3>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-400">Nombre completo</label>
                        <input type="text" value="{{ $user->name }}" disabled
                            class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-400">Email</label>
                        <input type="text" value="{{ $user->email }}" disabled
                            class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
                    </div>
                    <div>
                        <label for="phone" class="mb-1 block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    </div>
                    <div>
                        <label for="address" class="mb-1 block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    </div>
                    <div class="pt-2">
                        <x-ui.button variant="primary" size="sm" type="submit">Guardar Cambios</x-ui.button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Change password --}}
        <form method="POST" action="{{ route('docente.profile.password') }}">
            @csrf
            @method('PUT')
            <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Cambiar Contraseña</h3>
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="mb-1 block text-sm font-medium text-gray-700">Contraseña actual</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                        @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Nueva contraseña</label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    </div>
                    <div class="pt-2">
                        <x-ui.button variant="outline" size="sm" type="submit">Cambiar Contraseña</x-ui.button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
