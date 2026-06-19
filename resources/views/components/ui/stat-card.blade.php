@props([
    'value',
    'label',
    'icon' => null,
    'suffix' => null,
    'animated' => true,
    'color' => 'primary',
])

<div {{ $attributes->merge(['class' => 'text-center']) }}>
    @if($icon || isset($iconSlot))
        <x-ui.icon-box size="md" color="white" variant="ghost" class="mx-auto mb-3">
            @if(isset($iconSlot))
                {{ $iconSlot }}
            @endif
        </x-ui.icon-box>
    @endif

    <div class="text-3xl font-extrabold tracking-tight md:text-4xl lg:text-5xl"
        @if($animated)
            x-data="statsCounter({{ is_numeric($value) ? $value : 0 }})"
            x-text="value + '{{ $suffix ?? '' }}'"
        @endif
    >
        {{ $value }}{{ $suffix }}
    </div>

    <x-ui.text size="sm" color="inherit" class="mt-1 opacity-80">
        {{ $label }}
    </x-ui.text>
</div>
