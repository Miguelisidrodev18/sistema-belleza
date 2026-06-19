@props([
    'name',
    'maxWidth' => 'lg',
    'closeable' => true,
    'title' => null,
])

@php
    $maxWidthMap = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        'full' => 'sm:max-w-full sm:mx-4',
    ];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    @if($closeable) x-on:keydown.escape.window="show = false" @endif
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    {{ $attributes }}
>
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        @if($closeable) @click="show = false" @endif
    ></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full {{ $maxWidthMap[$maxWidth] ?? $maxWidthMap['lg'] }} overflow-hidden rounded-2xl bg-white shadow-xl"
            @click.stop
        >
            @if($title || $closeable)
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    @if($title)
                        <x-ui.heading :level="3" size="sm">{{ $title }}</x-ui.heading>
                    @else
                        <div></div>
                    @endif
                    @if($closeable)
                        <button @click="show = false" class="text-gray-400 transition-colors hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            <div class="p-6">
                {{ $slot }}
            </div>

            @if(isset($footer))
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
