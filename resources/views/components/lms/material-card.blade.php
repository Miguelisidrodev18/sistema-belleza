@props([
    'material',
    'role' => 'alumno',
    'downloadRoute' => 'alumno.materials.download',
])

@php
    $version = $material->currentVersion;
    $attachments = $version?->attachments ?? collect();
@endphp

<div class="rounded-lg border border-ugarte-border bg-white p-4 shadow-sm">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <h4 class="text-sm font-semibold text-gray-900">{{ $material->title }}</h4>
            @if($material->description)
                <p class="mt-0.5 text-xs text-gray-500 line-clamp-2">{{ $material->description }}</p>
            @endif
            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                <span>{{ $material->createdBy->name ?? 'Docente' }}</span>
                <span>&middot;</span>
                <span>{{ $material->created_at->format('d/m/Y') }}</span>
                @if($version)
                    <span>&middot;</span>
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[10px] text-gray-500">v{{ $version->version_number }}</span>
                @endif
                @if(($role === 'docente' || $role === 'admin') && $version)
                    <span>&middot;</span>
                    <span>{{ $version->download_count ?? 0 }} descargas</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Attachments --}}
    @if($attachments->isNotEmpty())
        <div class="mt-3 space-y-1.5">
            @foreach($attachments as $att)
                <div class="flex items-center gap-2 rounded-md bg-gray-50 px-3 py-2 text-sm">
                    <span class="shrink-0">{{ $att->type->icon() }}</span>
                    <span class="min-w-0 flex-1 truncate text-gray-700">
                        {{ $att->title ?? $att->original_name ?? $att->type->label() }}
                    </span>
                    @if($att->size_bytes)
                        <span class="shrink-0 text-xs text-gray-400">{{ $att->humanSize() }}</span>
                    @endif
                    @if($att->isLink())
                        <a href="{{ $att->path }}" target="_blank" rel="noopener"
                           class="shrink-0 text-xs font-medium text-ugarte-primary hover:underline">
                            Abrir
                        </a>
                    @else
                        <a href="{{ route($downloadRoute, $att) }}"
                           class="shrink-0 text-xs font-medium text-ugarte-primary hover:underline">
                            Descargar
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
