@props([
    'name',
    'label' => null,
    'options' => [],
    'placeholder' => 'Seleccionar...',
    'required' => false,
    'disabled' => false,
    'error' => null,
])

@php
    $errorMessage = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $hasError = (bool) $errorMessage;

    $classes = implode(' ', [
        'block w-full rounded-lg border bg-white px-4 py-3 text-base font-sans transition-all duration-200 appearance-none',
        $hasError
            ? 'border-error focus:ring-error/30 focus:border-error'
            : 'border-gray-300 focus:ring-ugarte-primary/30 focus:border-ugarte-primary',
        'focus:outline-none focus:ring-2',
        $disabled ? 'opacity-50 cursor-not-allowed bg-gray-50' : '',
    ]);
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ugarte-black">
            {{ $label }}
            @if($required) <span class="text-error">*</span> @endif
        </label>
    @endif

    <div class="relative">
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            class="{{ $classes }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $value => $optionLabel)
                <option value="{{ $value }}" @selected(old($name) == $value)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    @if($hasError)
        <p class="text-sm text-error">{{ $errorMessage }}</p>
    @endif
</div>
