@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-3">
        {{-- Left Side: Per Page Selector & Info --}}
        <div class="flex items-center gap-4 text-[13px] text-[#64748b]">
            <div class="flex items-center gap-2">
                <span>Tampilkan</span>
                <div class="relative">
                    <select 
                        class="h-9 appearance-none rounded-[10px] border border-[#e2e8f0] bg-white pl-3 pr-8 font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:ring-4 focus:ring-[#3b82f6]/10"
                        onchange="const url = new URL(window.location.href); url.searchParams.set('per_page', this.value); window.location.href = url.href"
                    >
                        @foreach([10, 25, 50, 100] as $perPage)
                            <option value="{{ $perPage }}" {{ $paginator->perPage() == $perPage ? 'selected' : '' }}>{{ $perPage }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m6 9 6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <span>data</span>
            </div>
            
            <span class="text-[#cbd5e1]">•</span>
            
            <div>
                Menampilkan 
                <span class="font-bold text-[#0f172a]">{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</span> 
                dari 
                <span class="font-bold text-[#0f172a]">{{ $paginator->total() }}</span>
            </div>
        </div>

        {{-- Right Side: Navigation Buttons --}}
        <div class="flex items-center gap-1">
            {{-- First Page --}}
            @if ($paginator->onFirstPage())
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m11 17-5-5 5-5m7 10-5-5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->url(1) }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#94a3b8] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m11 17-5-5 5-5m7 10-5-5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            @endif

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#94a3b8] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="flex items-center gap-1 mx-1">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="flex h-9 w-9 items-center justify-center text-[13px] font-bold text-[#cbd5e1]">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="flex h-9 w-9 items-center justify-center rounded-[10px] bg-[#3b82f6] text-[13px] font-black text-white shadow-lg shadow-blue-500/30">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[13px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#94a3b8] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m9 18 6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            @else
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m9 18 6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            @endif

            {{-- Last Page --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#94a3b8] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m13 17 5-5-5-5M6 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            @else
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-[10px] text-[#cbd5e1]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m13 17 5-5-5-5M6 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
