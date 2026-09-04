@props([
    'title' => null,
    'subtitle' => null,
    'as' => 'div',
])

<{{ $as }} {{ $attributes->merge(['class' => 'bg-white border border-stone-200/80 rounded-xl shadow-xs p-6 relative transition-all duration-200']) }}>
    @if ($title || isset($header) || isset($actions))
        <div class="flex items-start justify-between gap-4 mb-5 pb-3 border-b border-stone-100">
            <div>
                @if ($title)
                    <h3 class="font-heading font-semibold text-lg sm:text-xl text-stone-900 tracking-tight">
                        {{ $title }}
                    </h3>
                @endif
                @if ($subtitle)
                    <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                        {{ $subtitle }}
                    </p>
                @endif
                @isset($header)
                    {{ $header }}
                @endisset
            </div>

            @isset($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    {{ $slot }}
</{{ $as }}>
