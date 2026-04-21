{{-- Dashboard Partial: Guru Mata Pelajaran
     Konten dari pages/guru/dashboard.blade.php (tanpa <x-guru-shell> wrapper)
     Route sudah diupdate ke clean URL --}}

    {{-- STAT CARDS SUMMARY --}}
    <div class="mb-8 grid gap-4 grid-cols-1 sm:grid-cols-3">
        
        {{-- Card 1 --}}
        <section class="rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Kelas Diampu</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">6</span>
                <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Rombel</span>
            </div>
            <div class="mt-5 h-[4px] rounded-full bg-[#1d4ed8]"></div>
        </section>

        {{-- Card 2 --}}
        <section class="rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Penilaian Pending</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#3b82f6]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-end justify-between gap-3">
                <div class="flex items-end gap-2">
                    <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">12</span>
                    <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Siswa</span>
                </div>
                <svg class="mb-3 h-5 w-5 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="m3 17 6-6 4 4 7-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
            <div class="mt-5 h-[4px] rounded-full bg-[#3b82f6]"></div>
        </section>

        {{-- Card 3 --}}
        <section class="rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Tugas Terkumpul</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#60a5fa]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">24</span>
                <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Belum Dikoreksi</span>
            </div>
            <div class="mt-5 h-[4px] rounded-full bg-[#60a5fa]"></div>
        </section>

    </div>

    {{-- Grid Layout Utama --}}
    <div class="grid gap-6 lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_380px]">
        
        {{-- Kolom Kiri --}}
        <div class="space-y-6 flex flex-col">
            
            {{-- Jadwal Hari Ini --}}
            <div class="flex-1 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between border-b border-[#f1f5f9] pb-4">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.15em] text-[#0f172a]">Jadwal Hari Ini</p>
                        <p class="mt-1 text-[13px] text-[#64748b]">Senin, 14 Oktober 2026</p>
                    </div>
                    <a href="{{ route('jadwal') }}" class="text-[11px] font-bold text-[#1d4ed8] underline-offset-4 hover:underline">Lihat Kalender</a>
                </div>
                
                <div class="mt-5 space-y-0">
                    {{-- Item 1 --}}
                    <a href="{{ route('jadwal') }}" class="group flex items-center justify-between rounded-xl p-3 transition hover:bg-[#f8fafc] hover:shadow-sm cursor-pointer">
                        <div class="flex items-center gap-5">
                            <div class="text-right border-r-2 border-[#1d4ed8] pr-4 group-hover:border-[#2563eb] transition">
                                <p class="text-[14px] font-black tracking-tight text-[#0f172a]">07:30</p>
                                <p class="text-[11px] font-bold text-[#64748b]">09:00</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Bahasa Indonesia</p>
                                <p class="mt-0.5 text-[15px] font-bold text-[#0f172a]">Kelas VI - A</p>
                            </div>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#94a3b8] shadow-sm ring-1 ring-[#e2e8f0] transition group-hover:bg-[#1d4ed8] group-hover:text-white group-hover:ring-transparent group-hover:scale-105">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                    {{-- Item 2 --}}
                    <a href="{{ route('jadwal') }}" class="group flex items-center justify-between rounded-xl p-3 transition hover:bg-[#f8fafc] hover:shadow-sm cursor-pointer">
                        <div class="flex items-center gap-5">
                            <div class="text-right border-r-2 border-[#e2e8f0] group-hover:border-[#1d4ed8] transition pr-4">
                                <p class="text-[14px] font-black tracking-tight text-[#0f172a]">09:15</p>
                                <p class="text-[11px] font-bold text-[#64748b]">10:45</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Bahasa Indonesia</p>
                                <p class="mt-0.5 text-[15px] font-bold text-[#0f172a]">Kelas V - B</p>
                            </div>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#94a3b8] shadow-sm ring-1 ring-[#e2e8f0] transition group-hover:bg-[#1d4ed8] group-hover:text-white group-hover:ring-transparent group-hover:scale-105">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                    {{-- Item 3 --}}
                    <a href="{{ route('jadwal') }}" class="group flex items-center justify-between rounded-xl p-3 transition hover:bg-[#f8fafc] hover:shadow-sm cursor-pointer">
                        <div class="flex items-center gap-5">
                            <div class="text-right border-r-2 border-[#e2e8f0] group-hover:border-[#1d4ed8] transition pr-4">
                                <p class="text-[14px] font-black tracking-tight text-[#0f172a]">11:00</p>
                                <p class="text-[11px] font-bold text-[#64748b]">12:30</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Bahasa Indonesia</p>
                                <p class="mt-0.5 text-[15px] font-bold text-[#0f172a]">Kelas IV - A</p>
                            </div>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#94a3b8] shadow-sm ring-1 ring-[#e2e8f0] transition group-hover:bg-[#1d4ed8] group-hover:text-white group-hover:ring-transparent group-hover:scale-105">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Info Bottom Row --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl bg-[#f8fafc] p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Total Jam Mengajar</p>
                    <p class="mt-2 text-[24px] font-black tracking-tight text-[#0f172a]">6.5 Jam</p>
                </div>
                <div class="rounded-xl bg-[#f8fafc] p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Status Kehadiran</p>
                    <p class="mt-2 text-[24px] font-black tracking-tight text-[#16a34a]">Aktif</p>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-6">
            
            {{-- Dark Highlight Box --}}
            <div class="rounded-2xl bg-[#0f172a] p-6 text-white shadow-lg relative overflow-hidden">
                {{-- Decorative circles --}}
                <div class="absolute -top-12 -right-12 h-32 w-32 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-10 -left-10 h-24 w-24 rounded-full bg-white/5"></div>
                
                <div class="relative z-10">
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-white/70">Status Input Nilai</p>
                    <div class="mt-3 flex items-baseline gap-3">
                        <span class="text-[48px] font-black leading-none tracking-tighter">12</span>
                        <span class="text-[13px] font-bold leading-tight text-white/90">Siswa<br>Belum Dinilai</span>
                    </div>
                    <a href="{{ route('penilaian') }}" class="mt-6 flex w-full items-center justify-center rounded-lg bg-white px-4 py-3 text-[12px] font-black uppercase text-[#0f172a] transition hover:bg-[#f8fafc]">
                        Lengkapi Sekarang
                    </a>
                </div>
            </div>

            {{-- Akses Cepat --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#e2e8f0]">
                <div class="border-b border-[#f1f5f9] pb-4">
                    <h3 class="text-[12px] font-black uppercase tracking-[0.1em] text-[#0f172a]">Akses Cepat</h3>
                </div>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    <a href="{{ route('penilaian') }}" class="group flex flex-col items-center justify-center rounded-xl bg-[#f8fafc] p-4 text-center ring-1 ring-[#e2e8f0] transition hover:bg-[#1d4ed8] hover:ring-transparent">
                        <svg class="mb-2 h-6 w-6 text-[#3b82f6] transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span class="text-[11px] font-bold text-[#0f172a] group-hover:text-white">Input Nilai</span>
                    </a>
                    <a href="{{ route('capaian-kompetensi') }}" class="group flex flex-col items-center justify-center rounded-xl bg-[#f8fafc] p-4 text-center ring-1 ring-[#e2e8f0] transition hover:bg-[#1d4ed8] hover:ring-transparent">
                        <svg class="mb-2 h-6 w-6 text-[#3b82f6] transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-[11px] font-bold text-[#0f172a] group-hover:text-white">Capaian</span>
                    </a>
                    <a href="{{ route('siswa') }}" class="group flex flex-col items-center justify-center rounded-xl bg-[#f8fafc] p-4 text-center ring-1 ring-[#e2e8f0] transition hover:bg-[#1d4ed8] hover:ring-transparent">
                        <svg class="mb-2 h-6 w-6 text-[#3b82f6] transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="text-[11px] font-bold text-[#0f172a] group-hover:text-white">Data Siswa</span>
                    </a>
                    <a href="{{ route('jadwal') }}" class="group flex flex-col items-center justify-center rounded-xl bg-[#f8fafc] p-4 text-center ring-1 ring-[#e2e8f0] transition hover:bg-[#1d4ed8] hover:ring-transparent">
                        <svg class="mb-2 h-6 w-6 text-[#3b82f6] transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-[11px] font-bold text-[#0f172a] group-hover:text-white">Jadwal</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
