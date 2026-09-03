@props([
    'variant' => 'neutral', // neutral, brand, accent, success, warning, danger
    'size' => 'md',         // sm, md
])

@php
    $classes = match ($variant) {
        'brand' => 'bg-brand-50 text-brand-700 border-brand-200/70',
        'accent' => 'bg-amber-50 text-amber-800 border-amber-200',
        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        'danger' => 'bg-rose-50 text-rose-800 border-rose-200',
        default => 'bg-stone-100 text-stone-700 border-stone-200',
    };

    $sizeClasses = match ($size) {
        'sm' => 'text-[11px] px-2 py-0.5',
        'md' => 'text-xs px-2.5 py-1',
        default => 'text-xs px-2.5 py-1',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium border rounded-md tracking-normal {$classes} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>
