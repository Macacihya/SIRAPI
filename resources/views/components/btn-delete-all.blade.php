@props(['condition' => 'selectedCount > 1', 'count' => 'selectedCount', 'action' => 'showDeleteAll = true'])

<button x-show="{{ $condition }}" @click="{{ $action }}" x-transition {{ $attributes->merge(['class' => 'flex h-[38px] items-center gap-2 rounded-[8px] bg-[#dc2626] px-4 text-[12px] font-bold text-white transition hover:bg-[#b91c1c]']) }}>
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
    Hapus Semua (<span x-text="{{ $count }}"></span>)
</button>
