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
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Mata Pelajaran Diampu</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#3b82f6]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-end justify-between gap-3">
                <div class="flex items-end gap-2">
                    <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">2</span>
                    <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Mapel</span>
                </div>
            </div>
            <div class="mt-5 h-[4px] rounded-full bg-[#3b82f6]"></div>
        </section>

        {{-- Card 3 --}}
        <section class="rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Penilaian Pending</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#60a5fa]">
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
            <div class="mt-5 h-[4px] rounded-full bg-[#60a5fa]"></div>
        </section>

    </div>

    {{-- Grid Layout Utama --}}
    <div class="grid gap-6 lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_380px]">
        
        {{-- Kolom Kiri --}}
        <div class="space-y-6 flex flex-col">

            {{-- Progres Penilaian Kelas --}}
            <div class="flex-1 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between border-b border-[#f1f5f9] pb-4">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.15em] text-[#0f172a]">Progres Penilaian Kelas</p>
                        <p class="mt-1 text-[13px] text-[#64748b]">Status kelengkapan input nilai siswa</p>
                    </div>
                    <a href="{{ route('penilaian') }}" class="text-[11px] font-bold text-[#1d4ed8] underline-offset-4 hover:underline">Lihat Detail</a>
                </div>
                
                <div class="mt-5 space-y-4">
                    {{-- Item 1 --}}
                    <div class="group rounded-xl border border-[#e2e8f0] p-4 transition hover:bg-[#f8fafc] hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-[#0f172a]">Bahasa Indonesia (VI - A)</p>
                                    <p class="text-[12px] font-medium text-[#64748b]">28 dari 30 Siswa Dinilai</p>
                                </div>
                            </div>
                            <span class="text-[14px] font-black text-[#16a34a]">90%</span>
                        </div>
                        <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-[#f1f5f9]">
                            <div class="h-full rounded-full bg-[#16a34a] transition-all duration-500" style="width: 90%"></div>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="group rounded-xl border border-[#e2e8f0] p-4 transition hover:bg-[#f8fafc] hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-[#0f172a]">Bahasa Indonesia (V - B)</p>
                                    <p class="text-[12px] font-medium text-[#64748b]">10 dari 25 Siswa Dinilai</p>
                                </div>
                            </div>
                            <span class="text-[14px] font-black text-[#f59e0b]">40%</span>
                        </div>
                        <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-[#f1f5f9]">
                            <div class="h-full rounded-full bg-[#f59e0b] transition-all duration-500" style="width: 40%"></div>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="group rounded-xl border border-red-100 bg-red-50/50 p-4 transition hover:bg-red-50 hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-[#0f172a]">Bahasa Indonesia (IV - A)</p>
                                    <p class="text-[12px] font-medium text-red-600">0 dari 20 Siswa Dinilai (Perlu Tindakan)</p>
                                </div>
                            </div>
                            <span class="text-[14px] font-black text-red-600">0%</span>
                        </div>
                        <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-red-100">
                            <div class="h-full rounded-full bg-red-500 transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
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

                </div>
            </div>

        </div>
    </div>
