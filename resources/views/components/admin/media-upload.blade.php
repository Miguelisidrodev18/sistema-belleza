@props([
    'name',
    'accept' => 'image/*',
    'currentUrl' => null,
    'aspect' => 'aspect-video',
    'type' => 'image',
    'label' => null,
    'hint' => null,
    'removeAction' => null,
])

<div>
    @if($label)
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">{{ $label }}</p>
    @endif

    <div
        x-data="{
            preview: null,
            dragging: false,
            hasCurrent: {{ $currentUrl ? 'true' : 'false' }},
            onFile(file) {
                if (!file) return;
                this.preview = URL.createObjectURL(file);
            },
            onInput(e) { this.onFile(e.target.files[0]); },
            onDrop(e) {
                this.dragging = false;
                const file = e.dataTransfer.files[0];
                if (file) { $refs.input.files = e.dataTransfer.files; this.onFile(file); }
            },
        }"
        class="group relative {{ $aspect }} w-full cursor-pointer overflow-hidden rounded-xl border-2 border-dashed bg-gray-50 transition-colors duration-150"
        :class="dragging ? 'border-ugarte-primary bg-ugarte-primary/5' : 'border-ugarte-border hover:border-ugarte-primary/40'"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="onDrop($event)"
        @click="$refs.input.click()"
    >
        <input x-ref="input" type="file" name="{{ $name }}" accept="{{ $accept }}" class="hidden" @change="onInput($event)">

        {{-- New selection preview --}}
        <template x-if="preview">
            @if($type === 'video')
                <video :src="preview" class="absolute inset-0 h-full w-full object-cover" muted autoplay loop playsinline></video>
            @else
                <img :src="preview" class="absolute inset-0 h-full w-full object-cover" alt="">
            @endif
        </template>

        {{-- Existing media --}}
        @if($currentUrl)
            <template x-if="!preview">
                @if($type === 'video')
                    <video src="{{ $currentUrl }}" class="absolute inset-0 h-full w-full object-cover" muted autoplay loop playsinline></video>
                @else
                    <img src="{{ $currentUrl }}" class="absolute inset-0 h-full w-full object-cover" alt="">
                @endif
            </template>
        @endif

        {{-- Empty state --}}
        <div
            class="absolute inset-0 flex flex-col items-center justify-center gap-2 px-4 text-center text-gray-400"
            x-show="!preview && !hasCurrent"
        >
            <x-erp.icon :name="$type === 'video' ? 'film' : 'photo'" class="h-8 w-8" />
            <span class="text-xs font-medium">Click o arrastra un archivo aquí</span>
        </div>

        {{-- Hover overlay when media exists --}}
        <div
            class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 bg-black/0 text-white opacity-0 transition-all duration-150 group-hover:bg-black/50 group-hover:opacity-100"
            x-show="preview || hasCurrent"
        >
            <x-erp.icon name="photo" class="h-6 w-6" />
            <span class="text-xs font-semibold">Cambiar</span>
        </div>

        {{-- Drag indicator --}}
        <div
            class="pointer-events-none absolute inset-0 flex items-center justify-center bg-ugarte-primary/10"
            x-show="dragging"
            x-cloak
        >
            <span class="rounded-full bg-ugarte-primary px-3 py-1 text-xs font-semibold text-white">Suelta para subir</span>
        </div>
    </div>

    @if($hint)
        <p class="mt-2 text-xs text-gray-400">{{ $hint }}</p>
    @endif

    @if($removeAction)
        <form method="POST" action="{{ $removeAction }}" class="mt-1.5" onsubmit="return confirm('¿Quitar este archivo?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-700">
                Quitar archivo actual
            </button>
        </form>
    @endif
</div>
