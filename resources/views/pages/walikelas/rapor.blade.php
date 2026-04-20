{{-- Halaman: rapor --- menggunakan layout walikelas --}}
@extends('layouts.walikelas')
@section('title', 'Rapor Siswa')
@section('subtitle', 'Cetak dan kelola rapor')
@section('active', 'rapor')

@section('content')
user()"
    active="rapor"
    title="Rapor Siswa"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="grid gap-6 lg:grid-cols-[260px_1fr]" x-data="{ selected: 0, saveModalOpen: false, deleteModalOpen: false }">

        {{-- Student List Sidebar --}}
        @php
            $daftarSiswa = [
                ['init' => 'AA', 'nama' => 'Aditya Ardiansyah', 'nis' => '2122001'],
                ['init' => 'BP', 'nama' => 'Bagus Pratama', 'nis' => '2122002'],
                ['init' => 'CM', 'nama' => 'Citra Maharani', 'nis' => '2122003'],
                ['init' => 'DN', 'nama' => 'Dian Nugraha', 'nis' => '2122004'],
            ];
        @endphp

        <div class="rounded-xl bg-white ring-1 ring-[#e2e8f0]">
            <div class="border-b border-[#e2e8f0] px-5 py-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Daftar Siswa</p>
            </div>
            <div class="divide-y divide-[#f1f5f9]">
                @foreach ($daftarSiswa as $i => $ds)
                    <button
                        @click="selected = {{ $i }}"
                        class="flex w-full items-center gap-3 px-5 py-3.5 text-left transition"
                        :class="selected === {{ $i }} ? 'bg-[#eff6ff] border-l-4 border-[#1d4ed8]' : 'hover:bg-[#f8fafc] border-l-4 border-transparent'"
                    >
                        <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg text-[10px] font-bold"
                            :class="selected === {{ $i }} ? 'bg-[#1e40af] text-white' : 'bg-[#f1f5f9] text-[#1e40af]'">
                            {{ $ds['init'] }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-[13px] font-bold text-[#0f172a]">{{ $ds['nama'] }}</p>
                            <p class="text-[10px] text-[#64748b]">NIS: {{ $ds['nis'] }}</p>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Form Area --}}
        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Input Capaian Siswa</h1>
                    <p class="mt-1 text-[14px] text-[#475569]">Semester Ganjil 2023/2024 â€” <span x-text="['Aditya Ardiansyah','Bagus Pratama','Citra Maharani','Dian Nugraha'][selected]" class="font-semibold text-[#0f172a]">Aditya Ardiansyah</span></p>
                </div>
                <div class="flex items-center gap-2">
                    <button class="rounded-lg border border-[#e2e8f0] bg-white px-5 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Draft</button>
                    <button @click="saveModalOpen = true" class="rounded-lg bg-[#1d4ed8] px-5 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Simpan Rapor</button>
                </div>
            </div>

            {{-- Catatan + Saran --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Catatan Wali Kelas</p>
                    <textarea rows="5" placeholder="Masukkan evaluasi perkembangan karakter..." class="mt-2 w-full rounded-xl border border-[#e2e8f0] bg-white p-4 text-[13px] text-[#334155] placeholder-[#94a3b8] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></textarea>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Saran-saran</p>
                    <textarea rows="5" placeholder="Tuliskan saran untuk peningkatan..." class="mt-2 w-full rounded-xl border border-[#e2e8f0] bg-white p-4 text-[13px] text-[#334155] placeholder-[#94a3b8] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></textarea>
                </div>
            </div>

            {{-- Prestasi --}}
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Prestasi Siswa</p>
                    <button class="flex items-center gap-1 text-[12px] font-bold text-[#1d4ed8] transition hover:text-[#3b82f6]">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
                        Tambah Prestasi
                    </button>
                </div>
                <div class="mt-3 overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                                <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Jenis Kegiatan</th>
                                <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Keterangan / Tingkat</th>
                                <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-[#f1f5f9]">
                                <td class="px-5 py-3.5 font-semibold text-[#0f172a]">Olimpiade Matematika</td>
                                <td class="px-5 py-3.5 text-[#475569]">Juara 2 Tingkat Kabupaten</td>
                                <td class="px-5 py-3.5 text-center">
                                    <button @click="deleteModalOpen = true" class="text-[#dc2626] transition hover:text-[#b91c1c]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-[#f1f5f9]">
                                <td class="px-5 py-3.5 font-semibold text-[#0f172a]">Basket (Ekstrakurikuler)</td>
                                <td class="px-5 py-3.5 text-[#475569]">Anggota Inti Tim Sekolah</td>
                                <td class="px-5 py-3.5 text-center">
                                    <button @click="deleteModalOpen = true" class="text-[#dc2626] transition hover:text-[#b91c1c]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-5 py-3.5 text-[12px] italic text-[#94a3b8]">Klik baris ini untuk menambah data baru...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Verifikasi --}}
            <div class="flex items-center gap-4 rounded-xl bg-[#f1f5f9] p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex h-12 w-12 flex-none items-center justify-center rounded-full bg-[#dbeafe] text-[#1d4ed8]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                    <p class="text-[15px] font-bold text-[#0f172a]">Verifikasi Kelengkapan</p>
                    <p class="mt-0.5 text-[13px] text-[#475569]">Pastikan semua kolom input (Catatan, Prestasi, dan Saran) telah terisi dengan benar.</p>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS PRESTASI --}}
        <div x-show="deleteModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="deleteModalOpen = false">
            <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] mb-4 ring-4 ring-[#fee2e2]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Hapus Data Prestasi?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Data prestasi/catatan tambahan ini akan dihapus dari rapor siswa.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="deleteModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                    <button @click="deleteModalOpen = false; alert('Data terhapus!')" class="flex-1 rounded-lg bg-[#dc2626] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#b91c1c]">Ya, Hapus</button>
                </div>
            </div>
        </div>

        {{-- MODAL SIMPAN RAPOR --}}
        <div x-show="saveModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="saveModalOpen = false">
            <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8] mb-4 ring-4 ring-[#dbeafe]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Simpan Catatan Rapor?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Catatan wali kelas dan saran akan disimpan ke database rapor siswa.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="saveModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                    <button @click="saveModalOpen = false; alert('Rapor berhasil disimpan!')" class="flex-1 rounded-lg bg-[#1d4ed8] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

