@props([
    'title',
    'value',
    'icon' => null,
    'variant' => 'white', // 'white' or 'blue'
])

@php
    $baseClasses = "rounded-[14px] p-6 transition duration-200 hover:shadow-sm";
    $variantClasses = [
        'white' => "border border-[#e2e8f0] bg-white",
        'blue' => "bg-[#1d4ed8] text-white",
    ][$variant];

    $iconBgClasses = [
        'white' => "bg-[#f1f5f9] text-[#64748b]",
        'blue' => "bg-white/20 text-white",
    ][$variant];

    $titleClasses = [
        'white' => "text-[#64748b]",
        'blue' => "text-white/70",
    ][$variant];

    $valueClasses = [
        'white' => "text-[#0f172a]",
        'blue' => "text-white",
    ][$variant];
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
    @if($icon)
        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] {{ $iconBgClasses }}">
            {{ $icon }}
        </div>
    @endif
    <p class="text-[11px] font-bold uppercase tracking-[0.18em] {{ $titleClasses }}">{{ $title }}</p>
    <div class="mt-2 text-[36px] lg:text-[48px] font-black leading-none tracking-[-0.06em] {{ $valueClasses }}">
        @if(isset($valueSlot))
            {{ $valueSlot }}
        @else
            {{ $value }}
        @endif
    </div>
    
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
