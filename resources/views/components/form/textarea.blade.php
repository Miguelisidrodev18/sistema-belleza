@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
    'rows' => 4,
    'resize' => 'vertical',
])

@php
    $errorMessage = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $hasError = (bool) $errorMessage;

    $resizeMap = [
        'vertical' => 'resize-y',
        'none' => 'resize-none',
        'both' => 'resize',
    ];

    $inputClasses = implode(' ', [
        'block w-full rounded-lg border bg-white px-4 py-3 text-base font-sans transition-all duration-200',
        $hasError
            ? 'border-error focus:ring-error/30 focus:border-error'
            : 'border-gray-300 focus:ring-ugarte-primary/30 focus:border-ugarte-primary',
        'focus:outline-none focus:ring-2',
        $resizeMap[$resize] ?? $resizeMap['vertical'],
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

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        class="{{ $inputClasses }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
    >{{ old($name) }}</textarea>

    @if($hasError)
        <p class="text-sm text-error">{{ $errorMessage }}</p>
    @elseif($hint)
        <p class="text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
