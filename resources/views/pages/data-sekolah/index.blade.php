@extends('layouts.app')
@section('title', 'Data Sekolah')
@section('subtitle', 'Informasi institusi pendidikan')
@section('active', 'data-sekolah')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dataSekolahData', () => ({
            showUploadLogo: false,
            showUploadFoto: false,
            showBatalkan: false,
            logoUploaded: false,
            fotoUploaded: false,
        }));
    });
</script>

<form action="{{ route('sekolah.update', $sekolah->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="dataSekolahData">
    @csrf
    @method('PUT')

    {{-- ─── HEADING ─────────────────────────────────────── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Profil Lembaga</p>
            <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Identitas Sekolah</h1>
            <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Perbarui informasi dasar sekolah dan detail kepala sekolah.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="showBatalkan = true" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batalkan Perubahan</button>
            <button type="submit" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">Simpan Identitas</button>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-[13px] font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-[13px] font-semibold text-rose-800 space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Hidden File Inputs --}}
    <input type="file" name="logo" id="logoInput" class="hidden" @change="logoUploaded = true">

    {{-- ─── LOGO + INFO ────────────────────────────────── --}}
    <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6 text-center">
                <div class="mx-auto flex h-[120px] w-[120px] items-center justify-center rounded-[12px] bg-[#f1f5f9] overflow-hidden">
                    <template x-if="!logoUploaded">
                        @if($sekolah->logo)
                            <img src="{{ asset('storage/' . $sekolah->logo) }}" class="h-full w-full object-cover">
                        @else
                            <svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        @endif
                    </template>
                    <template x-if="logoUploaded">
                        <div class="flex flex-col items-center justify-center text-[#059669]">
                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path></svg>
                            <span class="text-[10px] font-bold mt-1">Logo Terpilih</span>
                        </div>
                    </template>
                </div>
                <p class="mt-3 text-[13px] font-bold text-[#0f172a]" x-text="logoUploaded ? 'Logo Baru Terpilih' : 'Logo Sekolah'"></p>
                <p class="mt-0.5 text-[11px] text-[#94a3b8]">Format PNG/JPG, Maks 2MB</p>
                <button type="button" @click="document.getElementById('logoInput').click()" class="mt-3 flex h-[36px] w-full items-center justify-center rounded-[6px] border border-[#e2e8f0] bg-white text-[12px] font-bold uppercase tracking-[0.08em] text-[#475569] transition hover:bg-[#f1f5f9]">Pilih File</button>
            </div>
        </div>
        <div class="space-y-6">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><span class="text-[#1d4ed8]">|</span> Informasi Utama</h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Sekolah</label>
                        <input name="nama_sekolah" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NPSN</label>
                        <input name="npsn" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('npsn', $sekolah->npsn) }}">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bentuk Pendidikan</label>
                        <input name="bentuk_pendidikan" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('bentuk_pendidikan', $sekolah->bentuk_pendidikan) }}">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status Sekolah</label>
                        <div class="mt-2 flex items-center gap-4">
                            <label class="flex items-center gap-2 text-[14px] font-medium text-[#0f172a] cursor-pointer">
                                <input type="radio" name="status_sekolah" value="Swasta" {{ old('status_sekolah', $sekolah->status_sekolah) === 'Swasta' ? 'checked' : '' }} class="h-4 w-4 accent-[#0f172a]"> Swasta
                            </label>
                            <label class="flex items-center gap-2 text-[14px] font-medium text-[#64748b] cursor-pointer">
                                <input type="radio" name="status_sekolah" value="Negeri" {{ old('status_sekolah', $sekolah->status_sekolah) === 'Negeri' ? 'checked' : '' }} class="h-4 w-4 accent-[#0f172a]"> Negeri
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><span class="text-[#1d4ed8]">|</span> Data Kepala Sekolah</h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-[120px_1fr_1fr]">
                    <div class="flex flex-col items-center gap-2 justify-center">
                        <div class="flex h-[80px] w-[80px] items-center justify-center rounded-full bg-[#f1f5f9] overflow-hidden">
                            <svg class="h-8 w-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama & Gelar</label>
                        <input name="nama_kepala_sekolah" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('nama_kepala_sekolah', $sekolah->nama_kepala_sekolah) }}">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP/NIY</label>
                        <input name="nip_kepsek" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('nip_kepsek', $sekolah->nip_kepsek) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── ALAMAT & KONTAK ─────────────────────────────── --}}
    <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
        <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><span class="text-[#1d4ed8]">|</span> Alamat & Kontak</h3>
        <div class="mt-5 grid gap-4 sm:grid-cols-[1fr_200px]">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Alamat Lengkap</label>
                <input name="alamat" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('alamat', $sekolah->alamat) }}">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Pos</label>
                <input name="kode_pos" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('kode_pos', $sekolah->kode_pos) }}">
            </div>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email Sekolah</label>
                <input name="email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('email', $sekolah->email) }}" type="email">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor Telepon</label>
                <input name="telepon" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ old('telepon', $sekolah->telepon) }}" type="tel">
            </div>
        </div>
        <div class="mt-5 flex h-[200px] items-center justify-center rounded-[10px] bg-[#f1f5f9] border border-[#e2e8f0]">
            <div class="text-center">
                <svg class="mx-auto h-8 w-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <p class="mt-2 text-[12px] font-semibold text-[#94a3b8]">Lokasi Terpeta Otomatis</p>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Konfirmasi Batalkan ═══ --}}
    <div x-show="showBatalkan" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showBatalkan = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] ring-4 ring-[#fee2e2]"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
                <h3 class="mt-4 text-[18px] font-black text-[#0f172a]">Batalkan Perubahan?</h3>
                <p class="mt-2 text-[13px] text-[#64748b]">Semua perubahan yang belum disimpan akan hilang. Tindakan ini tidak dapat diurungkan.</p>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button type="button" @click="showBatalkan = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Kembali</button>
                <button type="button" @click="window.location.reload()" class="flex-1 rounded-lg bg-[#dc2626] py-2.5 text-[12px] font-bold text-white">Ya, Batalkan</button>
            </div>
        </div>
    </div>
</form>
@endsection
