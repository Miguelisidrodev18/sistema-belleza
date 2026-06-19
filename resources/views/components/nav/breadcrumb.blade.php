@props([
    'items' => [],
    'separator' => 'chevron',
])

<nav aria-label="Breadcrumb" {{ $attributes }}>
    <ol class="flex items-center gap-2 text-sm">
        @foreach($items as $index => $item)
            <li class="flex items-center gap-2">
                @if($index > 0)
                    @if($separator === 'slash')
                        <span class="text-gray-400">/</span>
                    @elseif($separator === 'dot')
                        <span class="h-1 w-1 rounded-full bg-gray-400"></span>
                    @else
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                @endif

                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="text-gray-500 transition-colors hover:text-ugarte-primary">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="font-medium text-ugarte-black">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
