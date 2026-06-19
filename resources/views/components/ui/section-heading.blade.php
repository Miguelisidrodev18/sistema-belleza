@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'align' => 'center',
    'titleSize' => 'xl',
    'light' => false,
    'decorated' => true,
])

@php
    $wrapperClasses = $align === 'center' ? 'text-center' : 'text-left';
@endphp

<div {{ $attributes->merge(['class' => $wrapperClasses . ' mb-12 md:mb-16']) }}>
    @if($eyebrow)
        <x-ui.label :color="$light ? 'white' : 'primary'" class="mb-3 block">
            {{ $eyebrow }}
        </x-ui.label>
    @endif

    <x-ui.heading
        :level="2"
        :size="$titleSize"
        :color="$light ? 'white' : 'default'"
        weight="bold"
    >
        {{ $title }}
    </x-ui.heading>

    @if($decorated)
        <div class="mt-4 flex {{ $align === 'center' ? 'justify-center' : 'justify-start' }}">
            <div class="h-1 w-16 rounded-full {{ $light ? 'bg-white/60' : 'bg-ugarte-primary' }}"></div>
        </div>
    @endif

    @if($subtitle)
        <x-ui.text
            size="lg"
            :color="$light ? 'white' : 'muted'"
            class="mt-4 {{ $align === 'center' ? 'mx-auto max-w-2xl' : '' }}"
        >
            {{ $subtitle }}
        </x-ui.text>
    @endif

    @if($slot->isNotEmpty())
        <div class="mt-4 {{ $align === 'center' ? 'mx-auto max-w-2xl' : '' }}">
            {{ $slot }}
        </div>
    @endif
</div>
