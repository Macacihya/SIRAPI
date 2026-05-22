@props(['paginator'])

<div class="border-t border-[#e2e8f0] px-6 py-4">
    <nav class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap items-center gap-4 text-[13px] text-[#64748b]">
            <div class="flex items-center gap-2">
                <span>Tampilkan</span>
                <select disabled class="h-9 rounded-[10px] border border-[#e2e8f0] bg-white px-3 font-bold text-[#0f172a] outline-none">
                    <option>{{ $paginator->perPage() }}</option>
                </select>
                <span>data</span>
            </div>
            <span class="hidden text-[#cbd5e1] sm:inline">&bull;</span>
            <div>
                Menampilkan
                <span class="font-bold text-[#0f172a]">{{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}</span>
                dari
                <span class="font-bold text-[#0f172a]">{{ $paginator->total() }}</span>
            </div>
        </div>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m11 17-5-5 5-5m7 10-5-5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
                <span class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
            @else
                <a href="{{ $paginator->url(1) }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m11 17-5-5 5-5m7 10-5-5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </a>
                <a href="{{ $paginator->previousPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="flex h-9 w-9 items-center justify-center rounded-[10px] bg-[#3b82f6] text-[13px] font-black text-white shadow-lg shadow-blue-500/30">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[13px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </a>
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m13 17 5-5-5-5M6 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </a>
            @else
                <span class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
                <span class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m13 17 5-5-5-5M6 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
            @endif
        </div>
    </nav>
</div>
