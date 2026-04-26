{{-- ============================================================
     Komponen: Confirm Dialog
     Deskripsi: Dialog konfirmasi aksi (hapus, batalkan, dll).
     Menggantikan 4x kode identik di mata-pelajaran, siswa, dll.

     Props:
       - $alpineShow    : nama variabel Alpine (e.g., "showDelete")
       - $type          : "danger" (merah) atau "warning" (kuning)
       - $title         : judul dialog
       - $message       : pesan konfirmasi (bisa berisi HTML/Alpine)
       - $confirmText   : teks tombol konfirmasi (default: "Ya, Hapus")
       - $confirmAction : Alpine expression saat tombol konfirmasi diklik
       - $confirmColor  : warna tombol (default sesuai type)

     Output HTML IDENTIK dengan confirm dialog yang ada sebelumnya.
     ============================================================ --}}

@props([
    'alpineShow'    => 'showConfirm',
    'type'          => 'danger',
    'title'         => 'Konfirmasi',
    'message'       => 'Apakah Anda yakin?',
    'confirmText'   => 'Ya, Hapus',
    'confirmAction' => '',
    'confirmColor'  => '',
])

@php
    // Warna default berdasarkan type
    $iconBg    = $type === 'danger' ? 'bg-[#fef2f2]' : 'bg-[#fffbeb]';
    $iconColor = $type === 'danger' ? 'text-[#dc2626]' : 'text-[#d97706]';
    $iconRing  = $type === 'danger' ? 'ring-[#fee2e2]' : 'ring-[#fef3c7]';
    $btnColor  = $confirmColor ?: ($type === 'danger' ? 'bg-[#dc2626]' : 'bg-[#d97706]');
@endphp

<div
    x-show="{{ $alpineShow }}"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm"
    style="display:none"
    x-transition
    @click.self="{{ $alpineShow }} = false"
>
    <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
        <div class="p-6 text-center">
            {{-- Icon --}}
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full {{ $iconBg }} {{ $iconColor }} ring-4 {{ $iconRing }}">
                @if ($type === 'danger')
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                @else
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                @endif
            </div>
            <h3 class="mt-4 text-[18px] font-black text-[#0f172a]">{{ $title }}</h3>
            <p class="mt-2 text-[13px] text-[#64748b]">{!! $message !!}</p>
        </div>
        <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
            <button @click="{{ $alpineShow }} = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="{{ $confirmAction }}" class="flex-1 rounded-lg {{ $btnColor }} py-2.5 text-[12px] font-bold text-white">{{ $confirmText }}</button>
        </div>
    </div>
</div>
