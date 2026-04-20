{{-- Halaman: kehadiran --- menggunakan layout walikelas --}}
@extends('layouts.walikelas')
@section('title', 'Kehadiran Siswa')
@section('subtitle', 'Rekap kehadiran siswa')
@section('active', 'kehadiran')

@section('content')
user()"
    active="kehadiran"
    title="Kehadiran Siswa"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-6" x-data="{ saveModalOpen: false }">
        <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Kehadiran Siswa</h1>

        {{-- Controls --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex rounded-lg border border-[#e2e8f0] bg-white p-1">
                <button class="rounded-md bg-[#0f172a] px-4 py-2 text-[12px] font-bold text-white">Input Kehadiran</button>
                <button class="rounded-md px-4 py-2 text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">Rekap Kehadiran</button>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Pilih Tanggal</p>
                    <div class="mt-1 flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-3 py-2">
                        <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"/><path d="M16 3v4M8 3v4M3 10h18" stroke-linecap="round" stroke-width="2"/></svg>
                        <span class="text-[13px] font-semibold text-[#0f172a]">Senin, 24 Mei 2024</span>
                    </div>
                </div>
                <button @click="saveModalOpen = true" class="mt-5 rounded-lg bg-[#1d4ed8] px-5 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Simpan Kehadiran</button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_280px]">
            {{-- Attendance Table --}}
            <div class="rounded-xl bg-white ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] px-5 py-4">
                    <h3 class="text-[14px] font-bold text-[#0f172a]">Daftar Siswa Kelas XI-IPA 1</h3>
                    <span class="flex items-center gap-1.5 text-[12px] text-[#64748b]"><span class="h-1.5 w-1.5 rounded-full bg-[#0f172a]"></span> 32 Total Siswa</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                                <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">No</th>
                                <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Siswa</th>
                                <th class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Hadir</th>
                                <th class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Izin</th>
                                <th class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Sakit</th>
                                <th class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Alpa</th>
                                <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                ['no' => '01', 'nama' => 'Aditya Pratama', 'status' => 'hadir', 'ket' => ''],
                                ['no' => '02', 'nama' => 'Annisa Rahmawati', 'status' => 'izin', 'ket' => 'Acara Keluarga'],
                                ['no' => '03', 'nama' => 'Bagas Kurniawan', 'status' => 'hadir', 'ket' => ''],
                            ] as $s)
                                <tr class="border-b border-[#f1f5f9]">
                                    <td class="px-5 py-4 text-[#64748b]">{{ $s['no'] }}</td>
                                    <td class="px-5 py-4 font-bold text-[#0f172a]">{{ $s['nama'] }}</td>
                                    @foreach (['hadir', 'izin', 'sakit', 'alpa'] as $opt)
                                        <td class="px-3 py-4 text-center">
                                            <input type="radio" name="absen_{{ $s['no'] }}" value="{{ $opt }}" {{ $s['status'] === $opt ? 'checked' : '' }}
                                                class="h-5 w-5 border-2 border-[#cbd5e1] text-[#1d4ed8] focus:ring-[#3b82f6]/30">
                                        </td>
                                    @endforeach
                                    <td class="px-5 py-4 text-[12px] text-[#64748b]">{{ $s['ket'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-[#e2e8f0] px-5 py-3">
                    <p class="text-[12px] text-[#94a3b8]">Menampilkan 5 dari 32 siswa. Gunakan scroll untuk melihat lebih banyak.</p>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="space-y-4">
                {{-- Statistik --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Statistik Hari Ini</p>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-[#f1f5f9] p-3 text-center">
                            <p class="text-[32px] font-black leading-none text-[#0f172a]">28</p>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Hadir</p>
                        </div>
                        <div class="rounded-lg bg-[#eff6ff] p-3 text-center">
                            <p class="text-[32px] font-black leading-none text-[#1d4ed8]">2</p>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Izin</p>
                        </div>
                        <div class="rounded-lg bg-[#f1f5f9] p-3 text-center">
                            <p class="text-[32px] font-black leading-none text-[#475569]">1</p>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Sakit</p>
                        </div>
                        <div class="rounded-lg bg-[#fef2f2] p-3 text-center">
                            <p class="text-[32px] font-black leading-none text-[#dc2626]">1</p>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Alpa</p>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-[#e2e8f0] pt-3">
                        <div class="flex items-center justify-between text-[12px]">
                            <span class="text-[#475569]">Persentase Kehadiran</span>
                            <span class="font-bold text-[#0f172a]">87%</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-[#e2e8f0]"><div class="h-full w-[87%] rounded-full bg-[#1d4ed8]"></div></div>
                    </div>
                </div>

                {{-- Informasi --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Informasi</p>
                    <div class="mt-3 space-y-3">
                        <div class="flex gap-2">
                            <svg class="mt-0.5 h-3.5 w-3.5 flex-none text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"/></svg>
                            <p class="text-[12px] leading-relaxed text-[#475569]">Input kehadiran harus diselesaikan sebelum pukul 09:00 WIB.</p>
                        </div>
                        <div class="flex gap-2">
                            <svg class="mt-0.5 h-3.5 w-3.5 flex-none text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"/></svg>
                            <p class="text-[12px] leading-relaxed text-[#475569]">Siswa dengan status Alpa akan otomatis dikirim notifikasi ke orang tua.</p>
                        </div>
                    </div>
                </div>

                {{-- Kalender --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Kalender Akademik</p>
                    <div class="mt-3">
                        <p class="text-[32px] font-black leading-none text-[#0f172a]">MEI <span class="text-[18px] font-bold text-[#64748b]">2024</span></p>
                        <p class="mt-2 text-[13px] text-[#475569]">Minggu ke-4 Efektif</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL SIMPAN --}}
        <div x-show="saveModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="saveModalOpen = false">
            <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8] mb-4 ring-4 ring-[#dbeafe]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Simpan Kehadiran?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Pastikan semua data absensi harian siswa sudah benar sebelum disimpan.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="saveModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                    <button @click="saveModalOpen = false; alert('Data Kehadiran Berhasil Disimpan!')" class="flex-1 rounded-lg bg-[#1d4ed8] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Ya, Simpan</button>
                </div>
            </div>
        </div>

    </div>
@endsection

