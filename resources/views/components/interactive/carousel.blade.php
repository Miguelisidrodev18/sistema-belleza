@props([
    'autoplay' => true,
    'interval' => 5000,
    'showDots' => true,
    'showArrows' => true,
    'loop' => true,
    'pauseOnHover' => true,
])

<div
    x-data="carousel({ autoplay: {{ $autoplay ? 'true' : 'false' }}, interval: {{ $interval }}, loop: {{ $loop ? 'true' : 'false' }} })"
    @if($pauseOnHover) @mouseenter="paused = true" @mouseleave="paused = false" @endif
    {{ $attributes->merge(['class' => 'relative']) }}
>
    <div class="overflow-hidden">
        <div
            x-ref="track"
            class="flex transition-transform duration-500 ease-out"
            :style="'transform: translateX(-' + (current * 100) + '%)'"
        >
            {{ $slot }}
        </div>
    </div>

    @if($showArrows)
        <button
            @click="prev()"
            class="absolute left-2 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-ugarte-primary shadow-card transition-all hover:bg-white hover:shadow-card-hover md:left-4"
            aria-label="Anterior"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button
            @click="next()"
            class="absolute right-2 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-ugarte-primary shadow-card transition-all hover:bg-white hover:shadow-card-hover md:right-4"
            aria-label="Siguiente"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    @endif

    @if($showDots)
        <div class="mt-6 flex items-center justify-center gap-2">
            <template x-for="i in total" :key="i">
                <button
                    @click="goTo(i - 1)"
                    class="h-2.5 rounded-full transition-all duration-300"
                    :class="current === (i - 1) ? 'w-8 bg-ugarte-primary' : 'w-2.5 bg-ugarte-primary/30'"
                    :aria-label="'Ir a slide ' + i"
                ></button>
            </template>
        </div>
    @endif
</div>
