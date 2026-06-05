{{-- Dashboard Partial: Wali Kelas --}}

@php
    $waliData = $dashboardWalikelas ?? [];
    $attentionItems = $waliData['attention_items'] ?? collect();
    $raporProgress = $waliData['rapor_progress'] ?? 0;
@endphp

<div class="flex min-h-[calc(100vh-220px)] flex-col justify-between gap-6">
    <div class="grid gap-4 md:grid-cols-3">
        {{-- Card 1: Total Siswa --}}
        <section class="relative overflow-hidden rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Siswa</p>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $waliData['total_siswa'] ?? 0 }}</span>
                <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Peserta Didik</span>
            </div>
            <div class="mt-5 h-[4px] rounded-full bg-[#1d4ed8]"></div>
            {{-- Icon besar di tengah-kanan --}}
            <div class="absolute right-5 top-1/2 -translate-y-1/2 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#1d4ed8]">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </section>

        {{-- Card 2: Rapor Selesai (menggantikan Alpha Semester) --}}
        <section class="relative overflow-hidden rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Rapor Selesai</p>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $waliData['rapor_final'] ?? 0 }}</span>
                <span class="pb-2 text-[15px] font-semibold text-[#64748b]">/ {{ $waliData['total_siswa'] ?? 0 }} Final</span>
            </div>
            <div class="mt-5 h-[4px] rounded-full bg-[#3b82f6]"></div>
            {{-- Icon besar di tengah-kanan --}}
            <div class="absolute right-5 top-1/2 -translate-y-1/2 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#3b82f6]">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </section>

        {{-- Card 3: Rata-rata Nilai Kelas --}}
        <section class="relative overflow-hidden rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-[#e2e8f0]">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Rata-rata Nilai Kelas</p>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ number_format((float) ($waliData['rata_nilai'] ?? 0), 2) }}</span>
                <span class="pb-2 text-[15px] font-semibold text-[#64748b]">Semester Ini</span>
            </div>
            <div class="mt-5 h-[4px] rounded-full bg-[#60a5fa]"></div>
            {{-- Icon besar di tengah-kanan --}}
            <div class="absolute right-5 top-1/2 -translate-y-1/2 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#60a5fa]">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="m12 3 2.5 5.09 5.6.82-4.05 3.95.96 5.57L12 15.77l-5.01 2.66.96-5.57L3.9 8.91l5.6-.82L12 3Z" stroke-linejoin="round" stroke-width="2"/>
                </svg>
            </div>
        </section>
    </div>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_260px]">
        <section class="rounded-[14px] bg-[#f1f5f9] p-6 ring-1 ring-[#e2e8f0]">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="max-w-[560px]">
                    <h3 class="text-[20px] font-black tracking-[-0.04em] text-[#0f172a] md:text-[28px]">Progres Input Rapor</h3>
                    <p class="mt-2 text-[14px] leading-[1.7] text-[#475569] md:text-[16px]">
                        Pantau jumlah rapor siswa di kelas wali yang sudah difinalisasi.
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-[48px] font-black leading-none tracking-[-0.06em] text-[#0f172a] md:text-[56px]">{{ $waliData['rapor_final'] ?? 0 }}<span class="text-[24px] font-bold text-[#94a3b8]"> /{{ $waliData['total_siswa'] ?? 0 }}</span></div>
                </div>
            </div>

            <div class="mt-6 h-[42px] overflow-hidden rounded-[4px] bg-[#cbd5e1]">
                <div class="flex h-full items-center justify-end bg-[#1d4ed8] pr-4 text-[12px] font-bold uppercase tracking-[0.1em] text-white" style="width: {{ $raporProgress }}%">
                    {{ $raporProgress }}% Selesai
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <article class="rounded-[10px] bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 7h14a1 1 0 0 1 1 1v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a1 1 0 0 1 1-1Z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <p class="text-[22px] font-bold tracking-[-0.03em] text-[#0f172a]">{{ $waliData['rapor_pending'] ?? 0 }} Siswa Tersisa</p>
                    <p class="mt-2 text-[14px] leading-6 text-[#475569]">Rapor belum final.</p>
                </article>

                <article class="rounded-[10px] bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" stroke-width="2"></circle><path d="m9.5 12 1.7 1.7 3.8-3.8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <p class="text-[22px] font-bold tracking-[-0.03em] text-[#0f172a]">{{ $waliData['rapor_final'] ?? 0 }} Siswa Final</p>
                    <p class="mt-2 text-[14px] leading-6 text-[#475569]">Rapor sudah siap dicetak.</p>
                </article>
            </div>
        </section>

        <aside class="rounded-[14px] bg-white p-6 ring-1 ring-[#e2e8f0]">
            <div class="flex items-center gap-2 text-[#dc2626]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle><path d="M12 8v5M12 16h.01" stroke-linecap="round" stroke-width="2"></path></svg>
                <h3 class="text-[18px] font-black tracking-[-0.04em]">Perlu Perhatian</h3>
            </div>

            <div class="mt-5 space-y-5">
                @forelse ($attentionItems as $item)
                    <div class="flex gap-3">
                        <div class="flex h-11 w-11 flex-none items-center justify-center rounded-[6px] bg-[#f1f5f9] text-[12px] font-bold text-[#1e40af]">
                            {{ $item['initials'] }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[15px] font-bold text-[#0f172a]">{{ $item['name'] }}</p>
                            <p class="mt-1 text-[12px] leading-5 text-[#64748b]">{{ $item['detail'] }}</p>
                            <span class="mt-2 inline-flex rounded-[4px] px-2 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] {{ $item['badgeClass'] }}">
                                {{ $item['badge'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[10px] bg-[#f8fafc] p-4 text-center text-[13px] font-semibold text-[#64748b]">
                        Belum ada siswa yang perlu perhatian khusus.
                    </div>
                @endforelse
            </div>

            <a href="{{ route('penilaian') }}" class="mt-6 flex h-[46px] w-full items-center justify-center rounded-[4px] border border-[#cbd5e1] bg-white text-[12px] font-extrabold uppercase tracking-[0.12em] text-[#1e40af] transition hover:bg-[#f1f5f9]">
                Lihat Detail Semua
            </a>
        </aside>
    </div>
</div>
