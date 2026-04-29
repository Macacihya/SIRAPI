{{-- ============================================================
     Komponen: Modal (Generic Wrapper)
     Deskripsi: Wrapper modal yang bisa dipakai untuk SEMUA modal
     di seluruh halaman. Menghilangkan duplikasi backdrop,
     container, header, dan footer pattern.

     Props:
       - $alpineShow  : nama variabel Alpine (e.g., "showAdd")
       - $title       : judul modal di header
       - $maxWidth    : ukuran max modal (sm/md/lg), default "sm"

     Slots:
       - default ($slot) : konten body modal
       - $footer         : tombol-tombol di footer

     Contoh penggunaan:
       <x-modal alpineShow="showAdd" title="Tambah Siswa" maxWidth="lg">
           <div>...form fields...</div>
           <x-slot:footer>
               <button @click="showAdd = false">Batal</button>
               <button @click="submitAdd()">Simpan</button>
           </x-slot:footer>
       </x-modal>

     Output HTML IDENTIK dengan modal-modal yang ada sebelumnya.
     ============================================================ --}}

@props([
    'alpineShow' => 'showModal',
    'title' => 'Modal',
    'maxWidth' => 'sm',
])

@php
    // Mapping ukuran max-width sesuai pattern yang sudah ada
    $maxWidthClass = match ($maxWidth) {
        'sm'  => 'max-w-sm',
        'md'  => 'max-w-md',
        'lg'  => 'max-w-lg',
        'xl'  => 'max-w-xl',
        default => 'max-w-sm',
    };
@endphp

<template x-teleport="body">
    <div
        x-show="{{ $alpineShow }}"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm"
        style="display:none"
        x-transition
        @click.self="{{ $alpineShow }} = false"
    >
        <div class="w-[90%] {{ $maxWidthClass }} rounded-2xl bg-white shadow-2xl" @click.stop>
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                <h3 class="text-[18px] font-black text-[#0f172a]">{{ $title }}</h3>
                <button @click="{{ $alpineShow }} = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if (isset($footer))
                <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</template>
