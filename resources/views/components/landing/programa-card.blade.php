@props([
    'name',
    'slug',
    'color',
    'icon',
    'description',
    'duration',
    'image' => null,
])

<div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

    {{-- Visual header --}}
    <div class="relative overflow-hidden" style="height:176px">
        @if($image)
            <img src="{{ $image }}" alt="{{ $name }}"
                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0"
                 style="background:linear-gradient(to bottom,transparent 30%,rgba(0,0,0,0.55) 100%)"></div>
        @else
            {{-- Gradient background --}}
            <div class="absolute inset-0"
                 style="background:linear-gradient(135deg,{{ $color }} 0%,{{ $color }}bb 55%,{{ $color }}66 100%)"></div>
            {{-- Decorative circles --}}
            <div class="absolute rounded-full"
                 style="width:160px;height:160px;top:-48px;right:-48px;background:rgba(255,255,255,0.13)"></div>
            <div class="absolute rounded-full"
                 style="width:100px;height:100px;bottom:-28px;left:-28px;background:rgba(255,255,255,0.09)"></div>
            <div class="absolute rounded-full"
                 style="width:64px;height:64px;top:16px;left:38%;background:rgba(255,255,255,0.07)"></div>
        @endif

        {{-- Centered icon (only when no photo) --}}
        @unless($image)
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl"
                     style="background:rgba(255,255,255,0.2);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        @switch($icon)
                            @case('eye')
                            @case('pestanas')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                @break
                            @case('pencil')
                            @case('microblading')
                            @case('dermopigmentacion')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                @break
                            @case('briefcase')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                                @break
                            @case('barberia')
                            @case('estilismo')
                            @case('maquillaje')
                            @case('podologia')
                            @case('unas')
                            @case('sparkles')
                            @default
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                @break
                        @endswitch
                    </svg>
                </div>
            </div>
        @endunless

        {{-- Duration pill --}}
        <div class="absolute right-3 top-3">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                  style="background:rgba(255,255,255,0.92);color:{{ $color }}">
                {{ $duration }}
            </span>
        </div>
    </div>

    {{-- Card body --}}
    <div class="flex grow flex-col p-5">
        <h3 class="text-base font-bold leading-tight text-gray-900">{{ $name }}</h3>
        <p class="mt-2 grow text-sm leading-relaxed text-gray-500">{{ $description }}</p>
        <div class="mt-4 border-t border-gray-50 pt-4">
            <button type="button"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold transition-all duration-200 hover:gap-3"
                    style="color:{{ $color }}">
                Más información
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </div>
</div>
