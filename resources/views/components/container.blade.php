@props([
    'size' => 'wide', // 'wide' (max-w-5xl) or 'narrow' (max-w-2xl)
])

@php
    $maxWidth = match ($size) {
        'narrow' => 'max-w-2xl',
        'compact' => 'max-w-3xl',
        'wide' => 'max-w-5xl',
        'full' => 'max-w-7xl',
        default => 'max-w-5xl',
    };
@endphp

<div {{ $attributes->merge(['class' => "{$maxWidth} mx-auto px-4 sm:px-6 lg:px-8 w-full"]) }}>
    {{ $slot }}
</div>
