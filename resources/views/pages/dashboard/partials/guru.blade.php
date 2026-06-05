{{-- Dashboard Partial: Guru Mata Pelajaran --}}

@php
    $guruData = $dashboardGuru ?? [];
    $progressItems = $guruData['progress'] ?? collect();
@endphp

<div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <section class="relative overflow-hidden rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Kelas Diampu</p>
        <div class="mt-3 flex items-end gap-2">
            <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $guruData['kelas_count'] ?? 0 }}</span>
            <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Rombel</span>
        </div>
        <div class="mt-5 h-[4px] rounded-full bg-[#1d4ed8]"></div>
        <div class="absolute right-5 top-1/2 -translate-y-1/2 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#1d4ed8]">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
    </section>

    <section class="relative overflow-hidden rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Mata Pelajaran Diampu</p>
        <div class="mt-3 flex items-end gap-2">
            <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $guruData['mapel_count'] ?? 0 }}</span>
            <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Mapel</span>
        </div>
        <div class="mt-5 h-[4px] rounded-full bg-[#3b82f6]"></div>
        <div class="absolute right-5 top-1/2 -translate-y-1/2 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#3b82f6]">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </div>
    </section>

    <section class="relative overflow-hidden rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Penilaian Pending</p>
        <div class="mt-3 flex items-end gap-2">
            <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $guruData['pending_count'] ?? 0 }}</span>
            <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Entri Nilai</span>
        </div>
        <div class="mt-5 h-[4px] rounded-full bg-[#60a5fa]"></div>
        <div class="absolute right-5 top-1/2 -translate-y-1/2 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#60a5fa]">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
        </div>
    </section>
</div>

<div class="grid gap-6 lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_380px]">
    <div class="flex flex-col space-y-6">
        <div class="flex-1 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#e2e8f0]">
            <div class="flex items-center justify-between border-b border-[#f1f5f9] pb-4">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.15em] text-[#0f172a]">Progres Penilaian Kelas</p>
                    <p class="mt-1 text-[13px] text-[#64748b]">Status kelengkapan input nilai sesuai kelas dan mapel yang diampu</p>
                </div>
                <a href="{{ route('penilaian') }}" class="text-[11px] font-bold text-[#1d4ed8] underline-offset-4 hover:underline">Lihat Detail</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($progressItems as $item)
                    <div class="group rounded-xl border p-4 transition hover:shadow-sm {{ $item['box_class'] }}">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-[#0f172a]">{{ $item['mapel'] }} ({{ $item['kelas'] }})</p>
                                    <p class="text-[12px] font-medium text-[#64748b]">{{ $item['dinilai'] }} dari {{ $item['total_siswa'] }} siswa dinilai</p>
                                </div>
                            </div>
                            <span class="text-[14px] font-black {{ $item['text_class'] }}">{{ $item['percentage'] }}%</span>
                        </div>
                        <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-[#f1f5f9]">
                            <div class="h-full rounded-full {{ $item['bar_class'] }} transition-all duration-500" style="width: {{ $item['percentage'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-[#cbd5e1] p-8 text-center">
                        <p class="text-[14px] font-bold text-[#475569]">Belum ada kelas atau mapel yang diampu.</p>
                        <p class="mt-1 text-[12px] text-[#94a3b8]">Data akan muncul setelah admin mengatur guru pengampu.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-[#0f172a] p-6 text-white shadow-lg">
            <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-10 -left-10 h-24 w-24 rounded-full bg-white/5"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-white/70">Status Input Nilai</p>
                <div class="mt-3 flex items-baseline gap-3">
                    <span class="text-[48px] font-black leading-none tracking-tighter">{{ $guruData['pending_count'] ?? 0 }}</span>
                    <span class="text-[13px] font-bold leading-tight text-white/90">Entri<br>Belum Dinilai</span>
                </div>
                <a href="{{ route('penilaian') }}" class="mt-6 flex w-full items-center justify-center rounded-lg bg-white px-4 py-3 text-[12px] font-black uppercase text-[#0f172a] transition hover:bg-[#f8fafc]">
                    Lengkapi Sekarang
                </a>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#e2e8f0]">
            <div class="border-b border-[#f1f5f9] pb-4">
                <h3 class="text-[12px] font-black uppercase tracking-[0.1em] text-[#0f172a]">Akses Cepat</h3>
            </div>
            <div class="mt-5 grid grid-cols-2 gap-3">
                <a href="{{ route('penilaian') }}" class="group flex flex-col items-center justify-center rounded-xl bg-[#f8fafc] p-4 text-center ring-1 ring-[#e2e8f0] transition hover:bg-[#1d4ed8] hover:ring-transparent">
                    <svg class="mb-2 h-6 w-6 text-[#3b82f6] transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    <span class="text-[11px] font-bold text-[#0f172a] group-hover:text-white">Input Nilai</span>
                </a>
                <a href="{{ route('capaian-kompetensi') }}" class="group flex flex-col items-center justify-center rounded-xl bg-[#f8fafc] p-4 text-center ring-1 ring-[#e2e8f0] transition hover:bg-[#1d4ed8] hover:ring-transparent">
                    <svg class="mb-2 h-6 w-6 text-[#3b82f6] transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    <span class="text-[11px] font-bold text-[#0f172a] group-hover:text-white">Capaian</span>
                </a>
                <a href="{{ route('siswa') }}" class="group col-span-2 flex flex-col items-center justify-center rounded-xl bg-[#f8fafc] p-4 text-center ring-1 ring-[#e2e8f0] transition hover:bg-[#1d4ed8] hover:ring-transparent">
                    <svg class="mb-2 h-6 w-6 text-[#3b82f6] transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    <span class="text-[11px] font-bold text-[#0f172a] group-hover:text-white">Data Siswa</span>
                </a>
            </div>
        </div>
    </div>
</div>
