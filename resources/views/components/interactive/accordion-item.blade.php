@props([
    'index',
    'question',
    'variant' => 'default',
])

@php
    $wrapperClasses = match($variant) {
        'bordered' => 'rounded-lg border border-ugarte-border',
        'separated' => 'rounded-xl bg-ugarte-bg',
        default => 'border-b border-gray-200',
    };

    $buttonPadding = match($variant) {
        'bordered', 'separated' => 'px-5 py-4',
        default => 'py-5',
    };

    $contentPadding = match($variant) {
        'bordered', 'separated' => 'px-5 pb-4',
        default => 'pb-5',
    };
@endphp

<div class="{{ $wrapperClasses }}">
    <button
        @click="toggle({{ $index }})"
        class="flex w-full items-center justify-between text-left {{ $buttonPadding }}"
        :aria-expanded="isOpen({{ $index }})"
    >
        <span class="pr-4 text-base font-semibold text-ugarte-black md:text-lg">
            {{ $question }}
        </span>
        <span
            class="shrink-0 text-ugarte-primary transition-transform duration-300"
            :class="isOpen({{ $index }}) ? 'rotate-180' : ''"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </button>

    <div
        x-show="isOpen({{ $index }})"
        x-collapse
        class="{{ $contentPadding }}"
    >
        <x-ui.text size="base" color="muted">
            {{ $slot }}
        </x-ui.text>
    </div>
</div>
