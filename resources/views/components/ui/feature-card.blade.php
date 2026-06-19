@props([
    'icon' => null,
    'title',
    'description',
    'iconColor' => 'primary',
    'variant' => 'vertical',
    'size' => 'md',
])

@php
    $isHorizontal = $variant === 'horizontal';
@endphp

<x-ui.card :hover="true" variant="default" {{ $attributes }}>
    <div class="{{ $isHorizontal ? 'flex items-start gap-4' : 'text-center' }}">
        @if($icon || isset($iconSlot))
            <x-ui.icon-box :size="$size" :color="$iconColor" variant="filled" class="{{ $isHorizontal ? '' : 'mx-auto' }}">
                @if(isset($iconSlot))
                    {{ $iconSlot }}
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                @endif
            </x-ui.icon-box>
        @endif

        <div class="{{ $isHorizontal ? '' : 'mt-4' }}">
            <x-ui.heading :level="3" size="sm" class="mb-2">
                {{ $title }}
            </x-ui.heading>
            <x-ui.text size="sm" color="muted">
                {{ $description }}
            </x-ui.text>
        </div>
    </div>
</x-ui.card>
