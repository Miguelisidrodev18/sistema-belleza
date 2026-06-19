@props([
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
    'size' => 'md',
])

@php
    $errorMessage = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $hasError = (bool) $errorMessage;

    $sizeMap = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-3 text-base',
        'lg' => 'px-5 py-4 text-lg',
    ];

    $inputClasses = implode(' ', [
        'block w-full rounded-lg border bg-white font-sans transition-all duration-200',
        $sizeMap[$size] ?? $sizeMap['md'],
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
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name) }}"
        class="{{ $inputClasses }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
    >

    @if($hasError)
        <p class="text-sm text-error">{{ $errorMessage }}</p>
    @elseif($hint)
        <p class="text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
