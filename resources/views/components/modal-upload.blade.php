{{-- ============================================================
     Komponen: Modal Upload File
     Deskripsi: Modal upload file drag & drop yang siap pakai.
     Menggantikan 5x kode identik di profil, data-sekolah, dll.

     Props:
       - $alpineShow   : nama variabel Alpine (e.g., "showUploadFoto")
       - $title        : judul modal (e.g., "Upload Foto Profil")
       - $uploadAction : Alpine expression saat tombol upload diklik
       - $accept       : tipe file (default: "image/*")
       - $maxSize      : label ukuran max (default: "Maks 2MB")

     Output HTML IDENTIK dengan modal upload yang ada sebelumnya.
     ============================================================ --}}

@props([
    'alpineShow'   => 'showUploadFoto',
    'title'        => 'Upload File',
    'uploadAction' => '',
    'accept'       => 'image/*',
    'maxSize'      => 'Maks 2MB',
])

<x-modal :alpineShow="$alpineShow" :title="$title">
    <div class="flex h-[160px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#cbd5e1] bg-[#f8fafc] transition hover:border-[#3b82f6] cursor-pointer">
        <svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <p class="mt-2 text-[13px] font-bold text-[#475569]">Klik atau seret foto ke sini</p>
        <p class="mt-1 text-[11px] text-[#94a3b8]">PNG, JPG ({{ $maxSize }})</p>
    </div>

    <x-slot:footer>
        <button @click="{{ $alpineShow }} = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
        <button @click="{{ $uploadAction }}" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Upload</button>
    </x-slot:footer>
</x-modal>
