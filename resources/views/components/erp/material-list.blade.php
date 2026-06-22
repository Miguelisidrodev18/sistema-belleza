@props([
    'materials',
    'canManage' => false,
    'uploadAction' => null,
    'deleteRoutePrefix' => null,
    'routeParams' => [],
])

@if($materials->isEmpty())
<div class="rounded-xl border border-dashed border-ugarte-border bg-gray-50 py-12 text-center">
    <p class="text-sm text-gray-400">No hay materiales publicados aún.</p>
    @if($canManage)
    <p class="mt-1 text-xs text-gray-300">Sube el primer material usando el formulario de arriba.</p>
    @endif
</div>
@else
<div class="space-y-3">
    @foreach($materials as $material)
    @php $version = $material->currentVersion; @endphp
    <div class="rounded-xl border border-ugarte-border bg-white shadow-sm overflow-hidden">
        {{-- Material header --}}
        <div class="flex items-start justify-between gap-3 px-4 py-3 border-b border-ugarte-border bg-gray-50">
            <div class="min-w-0">
                <p class="font-semibold text-gray-800 text-sm truncate">{{ $material->title }}</p>
                @if($material->description)
                <p class="mt-0.5 text-xs text-gray-500 line-clamp-2">{{ $material->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                    {{ $material->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $material->is_published ? 'Publicado' : 'Borrador' }}
                </span>
                @if($version)
                <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-600">
                    v{{ $version->version_number }}
                </span>
                @endif
                @if($canManage && $deleteRoutePrefix)
                <form method="POST"
                      action="{{ route($deleteRoutePrefix, array_merge($routeParams, [$material])) }}"
                      onsubmit="return confirm('¿Eliminar este material?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="rounded p-1 text-gray-300 hover:bg-red-50 hover:text-red-500 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Attachments --}}
        @if($version && $version->attachments->isNotEmpty())
        <div class="divide-y divide-gray-50">
            @foreach($version->attachments as $attachment)
            <div class="flex items-center gap-3 px-4 py-2.5">
                <span class="text-lg flex-shrink-0">{{ $attachment->type->icon() }}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-700 truncate">
                        {{ $attachment->title ?? $attachment->original_name ?? $attachment->type->label() }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $attachment->type->label() }}
                        @if(! $attachment->isLink() && $attachment->size_bytes)
                        · {{ $attachment->humanSize() }}
                        @endif
                    </p>
                </div>
                @if($attachment->isLink())
                <a href="{{ $attachment->path }}" target="_blank" rel="noopener noreferrer"
                   class="flex-shrink-0 flex items-center gap-1.5 rounded-lg border border-ugarte-border px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Abrir
                </a>
                @elseif($uploadAction === null)
                {{-- Alumno download --}}
                <a href="{{ route('alumno.materials.download', $attachment) }}"
                   class="flex-shrink-0 flex items-center gap-1.5 rounded-lg bg-ugarte-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-ugarte-dark transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="px-4 py-3 text-xs text-gray-400 italic">Sin archivos adjuntos aún.</p>
        @endif
    </div>
    @endforeach
</div>
@endif
