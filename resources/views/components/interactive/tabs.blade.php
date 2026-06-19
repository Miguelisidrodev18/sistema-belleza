@props([
    'tabs' => [],
    'defaultTab' => null,
    'variant' => 'underline',
])

@php
    $firstTab = $defaultTab ?? ($tabs[0]['id'] ?? '');
@endphp

<div x-data="{ activeTab: '{{ $firstTab }}' }" {{ $attributes }}>
    <div class="flex gap-1 {{ $variant === 'underline' ? 'border-b border-gray-200' : '' }}">
        @foreach($tabs as $tab)
            <button
                @click="activeTab = '{{ $tab['id'] }}'"
                class="px-4 py-2.5 text-sm font-medium transition-all"
                :class="activeTab === '{{ $tab['id'] }}'
                    ? '{{ $variant === 'underline' ? 'border-b-2 border-ugarte-primary text-ugarte-primary' : 'bg-ugarte-primary text-white rounded-lg' }}'
                    : 'text-gray-500 hover:text-ugarte-primary'"
            >
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
