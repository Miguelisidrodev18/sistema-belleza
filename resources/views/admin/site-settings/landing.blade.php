<x-layouts.app title="Landing Page">
    <p class="-mt-4 mb-6 text-sm text-gray-500">
        Administra las imágenes y el video de la página principal del sitio web. Los cambios se reflejan al instante.
    </p>

    <div x-data="{ tab: 'hero' }">
        {{-- Tabs --}}
        <div class="mb-5 flex gap-1 overflow-x-auto rounded-lg bg-gray-100 p-1">
            @foreach([
                'hero' => ['label' => 'Hero (Portada)', 'icon' => 'photo'],
                'about' => ['label' => 'Quiénes Somos', 'icon' => 'photo'],
                'gallery' => ['label' => 'Galería', 'icon' => 'photo'],
            ] as $key => $meta)
                <button @click="tab = '{{ $key }}'" type="button"
                        :class="tab === '{{ $key }}' ? 'bg-white text-ugarte-primary shadow-sm font-medium' : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-2 whitespace-nowrap rounded-md px-4 py-2 text-sm transition-colors">
                    {{ $meta['label'] }}
                </button>
            @endforeach
        </div>

        {{-- Tab: Hero --}}
        <div x-show="tab === 'hero'" x-cloak class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900">Fondo del Hero</h2>
            <p class="mt-1 text-sm text-gray-500">Elige cómo se ve el fondo de la primera sección de la landing: un degradado, una imagen o un video con recorte personalizado.</p>

            <form
                x-data="{
                    type: '{{ old('hero_bg_type', $settings['hero_bg_type'] ?? 'gradient') }}',

                    // Image
                    imgPreview: null,
                    onImgSelect(e) {
                        const f = e.target.files[0];
                        this.imgPreview = f ? URL.createObjectURL(f) : null;
                    },

                    // Video
                    videoSrc: @js(($settings['hero_video'] ?? null) ? url('storage/' . $settings['hero_video']) : ''),
                    trimStart: {{ (float)($settings['hero_video_start'] ?? 0) }},
                    trimEnd:   {{ (float)($settings['hero_video_end'] ?? 0) }},
                    duration: 0,
                    previewing: false,

                    fmt(s) {
                        const m = Math.floor(s / 60);
                        const sec = Math.floor(s % 60);
                        return String(m).padStart(2,'0') + ':' + String(sec).padStart(2,'0');
                    },
                    pct(s) { return this.duration > 0 ? ((s / this.duration) * 100).toFixed(2) + '%' : '0%'; },

                    onVideoSelect(e) {
                        const f = e.target.files[0];
                        if (!f) return;
                        this.videoSrc = URL.createObjectURL(f);
                        this.trimStart = 0;
                        this.trimEnd = 0;
                        this.duration = 0;
                        this.previewing = false;
                        this.$nextTick(() => {
                            const v = this.$refs.trimVideo;
                            if (v) { v.load(); }
                        });
                    },
                    onMeta() {
                        const v = this.$refs.trimVideo;
                        if (!v) return;
                        this.duration = v.duration;
                        if (this.trimEnd === 0 || this.trimEnd > this.duration) {
                            this.trimEnd = Math.round(this.duration * 10) / 10;
                        }
                    },
                    onTimeUpdate() {
                        if (!this.previewing) return;
                        const v = this.$refs.trimVideo;
                        if (v && v.currentTime >= this.trimEnd) {
                            v.currentTime = this.trimStart;
                            v.play();
                        }
                    },
                    seekStart() {
                        const v = this.$refs.trimVideo;
                        if (v) { v.pause(); v.currentTime = this.trimStart; this.previewing = false; }
                    },
                    seekEnd() {
                        const v = this.$refs.trimVideo;
                        if (v) { v.pause(); v.currentTime = Math.max(0, this.trimEnd - 0.5); this.previewing = false; }
                    },
                    togglePreview() {
                        const v = this.$refs.trimVideo;
                        if (!v || !this.duration) return;
                        if (this.previewing) { v.pause(); this.previewing = false; return; }
                        v.currentTime = this.trimStart;
                        v.play();
                        this.previewing = true;
                    },
                }"
                method="POST" action="{{ route('admin.site-settings.landing.hero.update') }}"
                enctype="multipart/form-data" class="mt-5"
            >
                @csrf
                @method('PUT')

                {{-- Selector de tipo --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <label class="flex cursor-pointer flex-col items-center gap-2 rounded-lg border-2 p-4 text-center transition-colors"
                           :class="type === 'gradient' ? 'border-ugarte-primary bg-ugarte-primary/5' : 'border-ugarte-border hover:bg-gray-50'">
                        <input type="radio" name="hero_bg_type" value="gradient" x-model="type" class="sr-only">
                        <span class="h-10 w-16 rounded-md bg-gradient-to-br from-ugarte-primary to-ugarte-secondary"></span>
                        <span class="text-sm font-medium text-gray-700">Gradiente</span>
                    </label>
                    <label class="flex cursor-pointer flex-col items-center gap-2 rounded-lg border-2 p-4 text-center transition-colors"
                           :class="type === 'image' ? 'border-ugarte-primary bg-ugarte-primary/5' : 'border-ugarte-border hover:bg-gray-50'">
                        <input type="radio" name="hero_bg_type" value="image" x-model="type" class="sr-only">
                        <x-erp.icon name="photo" class="h-8 w-8 text-gray-400" />
                        <span class="text-sm font-medium text-gray-700">Imagen</span>
                    </label>
                    <label class="flex cursor-pointer flex-col items-center gap-2 rounded-lg border-2 p-4 text-center transition-colors"
                           :class="type === 'video' ? 'border-ugarte-primary bg-ugarte-primary/5' : 'border-ugarte-border hover:bg-gray-50'">
                        <input type="radio" name="hero_bg_type" value="video" x-model="type" class="sr-only">
                        <x-erp.icon name="film" class="h-8 w-8 text-gray-400" />
                        <span class="text-sm font-medium text-gray-700">Video</span>
                    </label>
                </div>

                {{-- Panel: Imagen --}}
                <div x-show="type === 'image'" x-cloak class="mt-6 border-t border-ugarte-border pt-5 sm:max-w-md">
                    <x-admin.media-upload
                        name="hero_image"
                        accept="image/*"
                        type="image"
                        aspect="aspect-video"
                        label="Imagen de fondo"
                        :currentUrl="($settings['hero_image'] ?? null) ? url('storage/' . $settings['hero_image']) : null"
                        :removeAction="($settings['hero_image'] ?? null) ? route('admin.site-settings.landing.remove-media', 'hero_image') : null"
                        hint="Recomendado: 1920×1080px o más. JPG, PNG o WEBP. Máx. 8MB."
                    />
                </div>

                {{-- Panel: Video con Trimmer --}}
                <div x-show="type === 'video'" x-cloak class="mt-6 border-t border-ugarte-border pt-5">
                    <input type="file" x-ref="videoInput" name="hero_video"
                           accept="video/mp4,video/webm,video/quicktime"
                           class="hidden" @change="onVideoSelect($event)">

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        {{-- Columna izquierda: player + botones --}}
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Video de fondo</p>

                            {{-- Player --}}
                            <div class="relative aspect-video w-full overflow-hidden rounded-xl bg-gray-900">
                                <template x-if="videoSrc">
                                    <video
                                        x-ref="trimVideo"
                                        :src="videoSrc"
                                        class="h-full w-full object-cover"
                                        muted playsinline preload="metadata"
                                        @loadedmetadata="onMeta()"
                                        @timeupdate="onTimeUpdate()"
                                    ></video>
                                </template>
                                <template x-if="!videoSrc">
                                    <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-gray-500">
                                        <x-erp.icon name="film" class="h-10 w-10 text-gray-600" />
                                        <span class="text-sm">Sin video cargado</span>
                                    </div>
                                </template>

                                {{-- Preview overlay indicator --}}
                                <div x-show="previewing" x-cloak
                                     class="pointer-events-none absolute bottom-3 left-3 flex items-center gap-1.5 rounded-full bg-black/60 px-3 py-1">
                                    <span class="h-2 w-2 animate-pulse rounded-full bg-red-400"></span>
                                    <span class="text-xs font-medium text-white">Vista previa</span>
                                </div>
                            </div>

                            {{-- Botones del video --}}
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <button type="button" @click="$refs.videoInput.click()"
                                        class="flex items-center gap-1.5 rounded-lg border border-ugarte-border bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    <x-erp.icon name="film" class="h-4 w-4" />
                                    <span x-text="videoSrc ? 'Cambiar video' : 'Subir video'"></span>
                                </button>

                                <button type="button" @click="togglePreview()" x-show="videoSrc && duration > 0"
                                        class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition-colors"
                                        :class="previewing
                                            ? 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100'
                                            : 'border-ugarte-primary/30 bg-ugarte-primary/5 text-ugarte-primary hover:bg-ugarte-primary/10'">
                                    <span x-text="previewing ? '⏸ Detener' : '▶ Vista previa del recorte'"></span>
                                </button>

                                @if($settings['hero_video'] ?? null)
                                    <form method="POST" action="{{ route('admin.site-settings.landing.remove-media', 'hero_video') }}"
                                          onsubmit="return confirm('¿Quitar el video actual?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">Quitar video</button>
                                    </form>
                                @endif
                            </div>
                            <p class="mt-2 text-xs text-gray-400">MP4 recomendado. Máx. 70MB (idealmente &lt;15MB comprimido).</p>
                        </div>

                        {{-- Columna derecha: trimmer --}}
                        <div x-show="videoSrc && duration > 0" x-cloak>
                            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Recorte del video</p>

                            {{-- Info del segmento --}}
                            <div class="mb-4 rounded-lg bg-ugarte-primary/5 px-4 py-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Segmento seleccionado</span>
                                    <span class="font-semibold text-ugarte-primary">
                                        <span x-text="fmt(trimEnd - trimStart)"></span> seg
                                    </span>
                                </div>
                                <div class="mt-0.5 flex items-center gap-1 text-xs text-gray-500">
                                    <span x-text="fmt(trimStart)"></span>
                                    <span>→</span>
                                    <span x-text="fmt(trimEnd)"></span>
                                    <span class="ml-2 text-gray-400">de <span x-text="fmt(duration)"></span> totales</span>
                                </div>
                            </div>

                            {{-- Timeline visual --}}
                            <div class="relative mb-5 h-3 rounded-full bg-gray-200">
                                {{-- Segmento inactivo izquierdo --}}
                                <div class="absolute inset-y-0 left-0 rounded-l-full bg-gray-300 transition-all"
                                     :style="`width: ${pct(trimStart)}`"></div>
                                {{-- Segmento activo --}}
                                <div class="absolute inset-y-0 rounded-full bg-ugarte-primary/80 transition-all"
                                     :style="`left: ${pct(trimStart)}; right: ${(duration > 0 ? ((1 - trimEnd/duration)*100).toFixed(2) : 0) + '%'}`"></div>
                                {{-- Marcador inicio --}}
                                <div class="absolute inset-y-0 w-1 -translate-x-0.5 rounded-full bg-ugarte-primary shadow"
                                     :style="`left: ${pct(trimStart)}`"></div>
                                {{-- Marcador final --}}
                                <div class="absolute inset-y-0 w-1 -translate-x-0.5 rounded-full bg-ugarte-primary shadow"
                                     :style="`left: ${pct(trimEnd)}`"></div>
                            </div>

                            {{-- Slider Inicio --}}
                            <div class="mb-3">
                                <div class="mb-1 flex items-center justify-between">
                                    <label class="text-xs font-semibold text-gray-600">Inicio</label>
                                    <span class="font-mono text-xs font-bold text-ugarte-primary" x-text="fmt(trimStart)"></span>
                                </div>
                                <input type="range"
                                       :value="trimStart"
                                       min="0" :max="trimEnd - 0.25" step="0.25"
                                       @input="trimStart = parseFloat($event.target.value); seekStart()"
                                       class="w-full cursor-pointer accent-ugarte-primary">
                            </div>

                            {{-- Slider Final --}}
                            <div class="mb-4">
                                <div class="mb-1 flex items-center justify-between">
                                    <label class="text-xs font-semibold text-gray-600">Final</label>
                                    <span class="font-mono text-xs font-bold text-ugarte-primary" x-text="fmt(trimEnd)"></span>
                                </div>
                                <input type="range"
                                       :value="trimEnd"
                                       :min="trimStart + 0.25" :max="duration" step="0.25"
                                       @input="trimEnd = parseFloat($event.target.value); seekEnd()"
                                       class="w-full cursor-pointer accent-ugarte-primary">
                            </div>

                            <p class="text-xs text-gray-400">El video reproducirá en bucle solo el segmento marcado. Arrastra los controles para ajustar.</p>

                            {{-- Hidden inputs para timestamps --}}
                            <input type="hidden" name="hero_video_start" :value="trimStart">
                            <input type="hidden" name="hero_video_end"   :value="trimEnd">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end border-t border-ugarte-border pt-5">
                    <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-ugarte-primary/90 transition-colors">
                        Guardar Hero
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab: Quiénes Somos --}}
        <div x-show="tab === 'about'" x-cloak class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900">Imagen de "¿Quiénes somos?"</h2>
            <p class="mt-1 text-sm text-gray-500">Fotografía institucional que acompaña el texto de presentación de la escuela.</p>

            <form method="POST" action="{{ route('admin.site-settings.landing.about.update') }}" enctype="multipart/form-data" class="mt-5 sm:max-w-md">
                @csrf
                @method('PUT')

                <x-admin.media-upload
                    name="about_image"
                    accept="image/*"
                    type="image"
                    aspect="aspect-[4/3]"
                    :currentUrl="($settings['about_image'] ?? null) ? url('storage/' . $settings['about_image']) : null"
                    :removeAction="($settings['about_image'] ?? null) ? route('admin.site-settings.landing.remove-media', 'about_image') : null"
                    hint="Recomendado: foto horizontal de las instalaciones o estudiantes. Mín. 1200×900px. Máx. 8MB."
                />

                <div class="mt-6 flex justify-end border-t border-ugarte-border pt-5">
                    <button type="submit" class="rounded-lg bg-ugarte-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-ugarte-primary/90 transition-colors">
                        Guardar imagen
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab: Galería --}}
        <div x-show="tab === 'gallery'" x-cloak class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900">Galería de fotos</h2>
            <p class="mt-1 text-sm text-gray-500">
                Sube varias fotos por categoría. En la landing se mostrarán en un carrusel. JPG, PNG o WEBP, máx. 8MB cada una.
            </p>

            <div class="mt-5 space-y-8">
                @foreach($galleryCategories as $slug => $label)
                    @php $catImages = $gallery->get($slug, collect()); @endphp

                    <div class="rounded-xl border border-ugarte-border p-5">
                        {{-- Header de categoría --}}
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-gray-800">{{ $label }}</h3>
                                @if($catImages->isNotEmpty())
                                    <span class="rounded-full bg-ugarte-primary/10 px-2 py-0.5 text-xs font-semibold text-ugarte-primary">
                                        {{ $catImages->count() }} foto{{ $catImages->count() !== 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Imágenes existentes --}}
                        @if($catImages->isNotEmpty())
                            <div class="mb-4 grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-6">
                                @foreach($catImages as $img)
                                    <div class="group relative aspect-square overflow-hidden rounded-lg bg-gray-100">
                                        <img src="{{ $img->url() }}" alt="" class="h-full w-full object-cover">
                                        {{-- Delete overlay --}}
                                        <form method="POST" action="{{ route('admin.gallery.destroy', $img) }}"
                                              class="absolute inset-0 flex items-center justify-center bg-black/0 opacity-0 transition-all duration-150 group-hover:bg-black/50 group-hover:opacity-100"
                                              onsubmit="return confirm('¿Eliminar esta imagen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600 transition-colors"
                                                    title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Zona de subida múltiple --}}
                        <form method="POST" action="{{ route('admin.gallery.store', $slug) }}"
                              enctype="multipart/form-data"
                              x-data="{
                                  previews: [],
                                  dragging: false,
                                  onFiles(files) {
                                      this.previews = Array.from(files).map(f => URL.createObjectURL(f));
                                      this.$refs.inp.files = files;
                                  },
                                  onDrop(e) {
                                      this.dragging = false;
                                      this.onFiles(e.dataTransfer.files);
                                  },
                              }">
                            @csrf

                            <input x-ref="inp" type="file" name="images[]" accept="image/*" multiple class="hidden"
                                   @change="onFiles($event.target.files)">

                            {{-- Dropzone --}}
                            <div class="relative cursor-pointer rounded-xl border-2 border-dashed py-6 text-center transition-colors duration-150"
                                 :class="dragging ? 'border-ugarte-primary bg-ugarte-primary/5' : 'border-ugarte-border hover:border-ugarte-primary/40 hover:bg-gray-50'"
                                 @dragover.prevent="dragging = true"
                                 @dragleave.prevent="dragging = false"
                                 @drop.prevent="onDrop($event)"
                                 @click="$refs.inp.click()">

                                <x-erp.icon name="photo" class="mx-auto mb-2 h-7 w-7 text-gray-400" />
                                <p class="text-sm font-medium text-gray-600">Click o arrastra fotos aquí</p>
                                <p class="mt-0.5 text-xs text-gray-400">Puedes seleccionar varias a la vez</p>
                            </div>

                            {{-- Previews de archivos seleccionados --}}
                            <template x-if="previews.length > 0">
                                <div class="mt-3">
                                    <p class="mb-2 text-xs font-semibold text-gray-500">
                                        <span x-text="previews.length"></span> foto(s) lista(s) para subir:
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(src, i) in previews" :key="i">
                                            <div class="relative h-16 w-16 overflow-hidden rounded-lg border border-ugarte-border">
                                                <img :src="src" class="h-full w-full object-cover" alt="">
                                                <div class="absolute inset-0 flex items-end justify-end p-0.5">
                                                    <button type="button"
                                                            @click.stop="previews.splice(i,1)"
                                                            class="flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white text-[10px]">
                                                        ×
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="submit"
                                            class="mt-3 rounded-lg bg-ugarte-primary px-4 py-2 text-sm font-medium text-white hover:bg-ugarte-primary/90 transition-colors">
                                        Subir <span x-text="previews.length"></span> foto(s) a "{{ $label }}"
                                    </button>
                                </div>
                            </template>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
