@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'p-6',
])

<div {{ $attributes->merge(['class' => "rounded-[14px] border border-[#e2e8f0] bg-white $padding"]) }}>
    @if($title || $subtitle || isset($action))
        <div class="mb-6 flex items-start justify-between">
            <div>
                @if($title)
                    <h3 class="text-[20px] font-black tracking-[-0.04em] text-[#0f172a]">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="mt-1 text-[13px] text-[#64748b]">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($action))
                <div class="flex-none">
                    {{ $action }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
