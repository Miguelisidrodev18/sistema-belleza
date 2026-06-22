<x-layouts.app title="Editar Usuario">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-ugarte-primary">
            <x-erp.icon name="chevron-left" class="h-4 w-4" />
            Volver a Usuarios
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.users._form', ['user' => $user])
    </form>
</x-layouts.app>
