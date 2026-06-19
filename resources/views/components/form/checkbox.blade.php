@props([
    'name',
    'label',
    'checked' => false,
    'value' => '1',
    'variant' => 'checkbox',
])

<label class="inline-flex cursor-pointer items-center gap-3" {{ $attributes }}>
    @if($variant === 'switch')
        <div class="relative" x-data="{ on: {{ $checked ? 'true' : 'false' }} }">
            <input type="hidden" name="{{ $name }}" value="0">
            <input
                type="checkbox"
                name="{{ $name }}"
                value="{{ $value }}"
                class="sr-only"
                x-model="on"
                @if($checked) checked @endif
            >
            <div
                class="h-6 w-11 rounded-full transition-colors duration-200"
                :class="on ? 'bg-ugarte-primary' : 'bg-gray-300'"
                @click="on = !on"
            >
                <div
                    class="h-5 w-5 translate-y-0.5 rounded-full bg-white shadow-sm transition-transform duration-200"
                    :class="on ? 'translate-x-[22px]' : 'translate-x-0.5'"
                ></div>
            </div>
        </div>
    @elseif($variant === 'radio')
        <input
            type="radio"
            name="{{ $name }}"
            value="{{ $value }}"
            class="h-4 w-4 border-gray-300 text-ugarte-primary focus:ring-ugarte-primary/30"
            @if($checked) checked @endif
        >
    @else
        <input
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary/30"
            @if($checked) checked @endif
        >
    @endif

    <span class="text-sm text-ugarte-black">{{ $label }}</span>
</label>
