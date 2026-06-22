<x-layouts.app title="Cambiar contraseña">
    <div class="flex min-h-[60vh] items-center justify-center">
        <div class="w-full max-w-md">
            <div class="rounded-xl border border-ugarte-border bg-white p-8 shadow-sm">

                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <strong class="block font-semibold">Cambio de contraseña obligatorio</strong>
                    Por seguridad, debes establecer una nueva contraseña antes de continuar.
                    Tu contraseña inicial era tu número de DNI.
                </div>

                <h1 class="mb-6 text-xl font-bold text-gray-900">Nueva contraseña</h1>

                <form method="POST" action="{{ route('docente.change-password.update') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-gray-700">
                            Nueva contraseña <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary @error('password') border-red-400 @enderror">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">
                            Confirmar contraseña <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-ugarte-primary focus:ring-1 focus:ring-ugarte-primary">
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-ugarte-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
                        Cambiar contraseña
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 underline">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
