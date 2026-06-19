@props([
    'value',
    'label',
    'prefix' => null,
    'suffix' => null,
    'duration' => 2000,
    'icon' => null,
])

<div
    {{ $attributes->merge(['class' => 'text-center']) }}
    x-data="statsCounter({{ is_numeric($value) ? $value : 0 }}, {{ $duration }})"
>
    <div class="text-3xl font-extrabold tracking-tight md:text-4xl lg:text-5xl">
        <span>{{ $prefix }}</span><span x-text="value">0</span><span>{{ $suffix }}</span>
    </div>
    <x-ui.text size="sm" color="inherit" class="mt-1 opacity-80">
        {{ $label }}
    </x-ui.text>
</div>
