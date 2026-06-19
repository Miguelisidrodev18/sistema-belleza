@props([
    'name',
    'role',
    'quote',
    'avatar' => null,
    'rating' => null,
])

<x-ui.card variant="glass" padding="lg" {{ $attributes }}>
    <div class="relative">
        <svg class="absolute -left-2 -top-2 h-8 w-8 text-ugarte-primary/20" fill="currentColor" viewBox="0 0 32 32">
            <path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm18 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/>
        </svg>

        <x-ui.text size="base" color="default" leading="relaxed" class="relative z-10 italic">
            "{{ $quote }}"
        </x-ui.text>

        @if($rating)
            <div class="mt-4 flex gap-1">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="h-4 w-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
        @endif

        <div class="mt-6 flex items-center gap-3">
            <x-ui.avatar :src="$avatar" :name="$name" size="md" />
            <div>
                <x-ui.text size="sm" weight="semibold" color="default" as="span" class="block">
                    {{ $name }}
                </x-ui.text>
                <x-ui.text size="xs" color="muted" as="span" class="block">
                    {{ $role }}
                </x-ui.text>
            </div>
        </div>
    </div>
</x-ui.card>
