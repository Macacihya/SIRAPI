@extends('layouts.app')
@section('title', 'Data Sekolah')
@section('subtitle', 'Informasi institusi pendidikan')
@section('active', 'data-sekolah')

@section('content')
<div x-data="{
    showUploadLogo: false,
    showUploadFoto: false,
    showBatalkan: false,
    logoUploaded: false,
    fotoUploaded: false,
}" class="space-y-6">

    {{-- ─── HEADING ─────────────────────────────────────── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Profil Lembaga</p>
            <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Identitas Sekolah</h1>
            <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Perbarui informasi dasar sekolah dan detail kepala sekolah.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showBatalkan = true" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batalkan Perubahan</button>
            <button @click="$dispatch('toast', {message: 'Identitas sekolah berhasil disimpan!', type: 'success'})" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">Simpan Identitas</button>
        </div>
    </div>

    {{-- ─── LOGO + INFO ────────────────────────────────── --}}
    <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6 text-center">
                <div class="mx-auto flex h-[120px] w-[120px] items-center justify-center rounded-[12px] bg-[#f1f5f9]">
                    <template x-if="!logoUploaded"><svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></template>
                    <template x-if="logoUploaded"><svg class="h-10 w-10 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path></svg></template>
                </div>
                <p class="mt-3 text-[13px] font-bold text-[#0f172a]" x-text="logoUploaded ? 'Logo Terupload' : 'Logo Sekolah'"></p>
                <p class="mt-0.5 text-[11px] text-[#94a3b8]">Format PNG/JPG, Maks 2MB</p>
                <button @click="showUploadLogo = true" class="mt-3 flex h-[36px] w-full items-center justify-center rounded-[6px] border border-[#e2e8f0] bg-white text-[12px] font-bold uppercase tracking-[0.08em] text-[#475569] transition hover:bg-[#f1f5f9]">Upload File</button>
            </div>
        </div>
        <div class="space-y-6">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><span class="text-[#1d4ed8]">|</span> Informasi Utama</h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Sekolah</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="SD Negeri 01 Indonesia"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NPSN</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="20304857"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bentuk Pendidikan</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none" value="SD (Sekolah Dasar)" readonly></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status Sekolah</label><div class="mt-2 flex items-center gap-4"><label class="flex items-center gap-2 text-[14px] font-medium text-[#0f172a] cursor-pointer"><input type="radio" name="status" checked class="h-4 w-4 accent-[#0f172a]"> Swasta</label><label class="flex items-center gap-2 text-[14px] font-medium text-[#64748b] cursor-pointer"><input type="radio" name="status" class="h-4 w-4 accent-[#0f172a]"> Negeri</label></div></div>
                </div>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><span class="text-[#1d4ed8]">|</span> Data Kepala Sekolah</h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-[120px_1fr_1fr]">
                    <div class="flex flex-col items-center gap-2">
                        <div class="flex h-[80px] w-[80px] items-center justify-center rounded-full bg-[#f1f5f9]">
                            <template x-if="!fotoUploaded"><svg class="h-8 w-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></template>
                            <template x-if="fotoUploaded"><svg class="h-8 w-8 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path></svg></template>
                        </div>
                        <button @click="showUploadFoto = true" class="text-[10px] font-bold text-[#1d4ed8] cursor-pointer hover:underline">Ganti Foto</button>
                    </div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama & Gelar</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="Dr. Budi Santoso, M.Pd."></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP/NIY</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="197508212003121002"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── ALAMAT & KONTAK ─────────────────────────────── --}}
    <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
        <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><span class="text-[#1d4ed8]">|</span> Alamat & Kontak</h3>
        <div class="mt-5 grid gap-4 sm:grid-cols-[1fr_200px]">
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Alamat Lengkap</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="Jl. Teknologi Cerdas No. 45, Kebayoran Baru, Jakarta Selatan"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Pos</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="12110"></div>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email Sekolah</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="info@smk-tu.sch.id" type="email"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor Telepon</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="(021) 7654321" type="tel"></div>
        </div>
        <div class="mt-5 flex h-[200px] items-center justify-center rounded-[10px] bg-[#f1f5f9] border border-[#e2e8f0]"><div class="text-center"><svg class="mx-auto h-8 w-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg><p class="mt-2 text-[12px] font-semibold text-[#94a3b8]">Lat: -6.2146 | Long: 106.8451</p></div></div>
    </div>

    {{-- ═══ MODAL: Upload Logo ═══ --}}
    <div x-show="showUploadLogo" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showUploadLogo = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Upload Logo Sekolah</h3><button @click="showUploadLogo = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="p-6">
                <div class="flex h-[160px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#cbd5e1] bg-[#f8fafc] transition hover:border-[#3b82f6] cursor-pointer">
                    <svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <p class="mt-2 text-[13px] font-bold text-[#475569]">Klik atau seret file ke sini</p>
                    <p class="mt-1 text-[11px] text-[#94a3b8]">PNG, JPG (Maks 2MB)</p>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showUploadLogo = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="logoUploaded = true; showUploadLogo = false; $dispatch('toast', {message: 'Logo sekolah berhasil diupload!', type: 'success'})" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Upload</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Upload Foto Kepsek ═══ --}}
    <div x-show="showUploadFoto" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showUploadFoto = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Ganti Foto Kepala Sekolah</h3><button @click="showUploadFoto = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="p-6">
                <div class="flex h-[160px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#cbd5e1] bg-[#f8fafc] transition hover:border-[#3b82f6] cursor-pointer">
                    <svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"></path><circle cx="12" cy="13" r="3" stroke-width="2"></circle></svg>
                    <p class="mt-2 text-[13px] font-bold text-[#475569]">Pilih foto profil</p>
                    <p class="mt-1 text-[11px] text-[#94a3b8]">PNG, JPG (Maks 2MB)</p>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showUploadFoto = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="fotoUploaded = true; showUploadFoto = false; $dispatch('toast', {message: 'Foto kepala sekolah berhasil diganti!', type: 'success'})" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Simpan</button>
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
                <button @click="showBatalkan = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Kembali</button>
                <button @click="showBatalkan = false; $dispatch('toast', {message: 'Perubahan berhasil dibatalkan.', type: 'info'})" class="flex-1 rounded-lg bg-[#dc2626] py-2.5 text-[12px] font-bold text-white">Ya, Batalkan</button>
            </div>
        </div>
    </div>

</div>
@endsection
