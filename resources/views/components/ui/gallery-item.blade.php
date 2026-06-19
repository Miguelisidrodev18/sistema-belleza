@props([
    'src',
    'alt',
    'caption' => null,
    'category' => null,
    'index' => 0,
])

<div
    {{ $attributes->merge(['class' => 'group relative cursor-pointer overflow-hidden rounded-xl']) }}
    @click="openLightbox({{ $index }})"
>
    <x-ui.lazy-image
        :src="$src"
        :alt="$alt"
        aspect="square"
        rounded="none"
        class="transition-transform duration-500 group-hover:scale-110"
    />

    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
        @if($caption)
            <div class="p-4">
                <x-ui.text size="sm" color="white" weight="medium">{{ $caption }}</x-ui.text>
            </div>
        @endif
    </div>

    <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/90 text-ugarte-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
            </svg>
        </div>
    </div>
</div>
