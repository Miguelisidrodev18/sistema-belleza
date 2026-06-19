{{-- Managed by Alpine.js gallery() component --}}

<template x-if="lightboxOpen">
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="closeLightbox()"
        @keydown.left.window="prevImage()"
        @keydown.right.window="nextImage()"
    >
        {{-- Close button --}}
        <button
            @click="closeLightbox()"
            class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20"
            aria-label="Cerrar"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Previous button --}}
        <button
            @click="prevImage()"
            class="absolute left-4 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20"
            aria-label="Anterior"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        {{-- Image --}}
        <div class="max-h-[85vh] max-w-[90vw]" @click.stop>
            <img
                :src="images[currentImage]?.src"
                :alt="images[currentImage]?.alt || 'Imagen de galería'"
                class="max-h-[85vh] max-w-full rounded-lg object-contain"
            >
        </div>

        {{-- Next button --}}
        <button
            @click="nextImage()"
            class="absolute right-4 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20"
            aria-label="Siguiente"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        {{-- Counter --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 rounded-full bg-white/10 px-4 py-1.5 text-sm text-white">
            <span x-text="(currentImage + 1) + ' / ' + images.length"></span>
        </div>

        {{-- Backdrop click to close --}}
        <div class="absolute inset-0 -z-10" @click="closeLightbox()"></div>
    </div>
</template>
