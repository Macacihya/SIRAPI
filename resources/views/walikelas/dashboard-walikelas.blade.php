<x-walikelas-shell
    :user="$user"
    active="dashboard"
    title="Dashboard Utama"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-6">
        <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_280px]">
            <div class="space-y-4">
                <div class="grid gap-4 md:grid-cols-3">
                    <section class="rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-black/5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#8a8a8a]">Total Siswa</p>
                        <div class="mt-3 flex items-end justify-between gap-3">
                            <div class="flex items-end gap-2">
                                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#171717]">36</span>
                                <span class="pb-2 text-[15px] font-semibold text-[#7a7a7a]">Peserta Didik</span>
                            </div>
                        </div>
                        <div class="mt-5 h-[4px] rounded-full bg-black"></div>
                    </section>

                    <section class="rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-black/5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#8a8a8a]">Kehadiran Hari Ini</p>
                        <div class="mt-3 flex items-end justify-between gap-3">
                            <div class="flex items-end gap-2">
                                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#171717]">95%</span>
                            </div>
                            <svg class="mb-3 h-5 w-5 text-[#8a8a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="m3 17 6-6 4 4 7-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="mt-5 h-[4px] rounded-full bg-black"></div>
                    </section>

                    <section class="rounded-[12px] bg-white px-5 py-5 shadow-[0_1px_0_rgba(0,0,0,0.05)] ring-1 ring-black/5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#8a8a8a]">Rata-rata Nilai Kelas</p>
                        <div class="mt-3 flex items-end justify-between gap-3">
                            <div class="flex items-end gap-2">
                                <span class="text-[56px] font-black leading-none tracking-[-0.06em] text-[#171717]">82.5</span>
                                <span class="pb-2 text-[15px] font-semibold text-[#7a7a7a]">Semester Ini</span>
                            </div>
                        </div>
                        <div class="mt-5 h-[4px] rounded-full bg-black"></div>
                    </section>
                </div>

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_260px]">
                    <section class="rounded-[14px] bg-[#f3f3f1] p-6 ring-1 ring-black/5">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div class="max-w-[560px]">
                                <h3 class="text-[20px] font-black tracking-[-0.04em] text-[#1e1e1e] md:text-[28px]">Progres Input Rapor</h3>
                                <p class="mt-2 text-[14px] leading-[1.7] text-[#656565] md:text-[16px]">
                                    Lengkapi data nilai dan deskripsi kompetensi siswa untuk pengisian rapor semester ganjil.
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-[48px] font-black leading-none tracking-[-0.06em] text-[#171717] md:text-[56px]">25<span class="text-[24px] font-bold text-[#8b8b8b]">/36</span></div>
                            </div>
                        </div>

                        <div class="mt-6 h-[42px] overflow-hidden rounded-[4px] bg-[#dfdfdf]">
                            <div class="flex h-full w-[69%] items-center justify-end bg-black pr-4 text-[12px] font-bold uppercase tracking-[0.1em] text-white">
                                69% Selesai
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <article class="rounded-[10px] bg-white p-5 ring-1 ring-black/5">
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#f2f5fa] text-[#1a3a6b]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 7V3m8 4V3M4 11h16M5 7h14a1 1 0 0 1 1 1v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a1 1 0 0 1 1-1Z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                        <path d="M12 14v3m0 0-1.5-1.5M12 17l1.5-1.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                </div>
                                <p class="text-[22px] font-bold tracking-[-0.03em] text-[#1d1d1d]">11 Siswa Tersisa</p>
                                <p class="mt-2 text-[14px] leading-6 text-[#717171]">Belum memiliki input nilai akhir.</p>
                            </article>

                            <article class="rounded-[10px] bg-white p-5 ring-1 ring-black/5">
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#f2f5fa] text-[#1a3a6b]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="8" stroke-width="2"></circle>
                                        <path d="m9.5 12 1.7 1.7 3.8-3.8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                </div>
                                <p class="text-[22px] font-bold tracking-[-0.03em] text-[#1d1d1d]">25 Siswa Valid</p>
                                <p class="mt-2 text-[14px] leading-6 text-[#717171]">Data sudah siap untuk dicetak.</p>
                            </article>
                        </div>
                    </section>

                    <aside class="rounded-[14px] bg-white p-6 ring-1 ring-black/5">
                        <div class="flex items-center gap-2 text-[#d63c3c]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
                                <path d="M12 8v5M12 16h.01" stroke-linecap="round" stroke-width="2"></path>
                            </svg>
                            <h3 class="text-[18px] font-black tracking-[-0.04em]">Perlu Perhatian</h3>
                        </div>

                        <div class="mt-5 space-y-5">
                            @foreach ([
                                ['initials' => 'AD', 'name' => 'Aditya Dharmawan', 'detail' => 'Absensi: 4 Hari Tanpa Keterangan', 'badge' => 'KRITIS', 'badgeClass' => 'bg-[#ffe1e1] text-[#b61d1d]'],
                                ['initials' => 'BS', 'name' => 'Bunga Salasbila', 'detail' => 'Nilai MTK: 65 (Dibawah KKM 75)', 'badge' => 'PERINGATAN', 'badgeClass' => 'bg-[#f0f0f0] text-[#6d6d6d]'],
                                ['initials' => 'FP', 'name' => 'Fahri Pratama', 'detail' => 'Nilai IPA: 68 (Dibawah KKM 75)', 'badge' => 'PERINGATAN', 'badgeClass' => 'bg-[#f0f0f0] text-[#6d6d6d]'],
                            ] as $item)
                                <div class="flex gap-3">
                                    <div class="flex h-11 w-11 flex-none items-center justify-center rounded-[6px] bg-[#f0f0ef] text-[12px] font-bold text-[#555]">
                                        {{ $item['initials'] }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[15px] font-bold text-[#232323]">{{ $item['name'] }}</p>
                                        <p class="mt-1 text-[12px] leading-5 text-[#7a7a7a]">{{ $item['detail'] }}</p>
                                        <span class="mt-2 inline-flex rounded-[4px] px-2 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] {{ $item['badgeClass'] }}">
                                            {{ $item['badge'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="mt-6 flex h-[46px] w-full items-center justify-center rounded-[4px] border border-black/10 bg-white text-[12px] font-extrabold uppercase tracking-[0.12em] text-[#2b2b2b] transition hover:bg-black/5" type="button">
                            Lihat Detail Semua
                        </button>
                    </aside>
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-[280px_minmax(0,1fr)_280px]">
            <section class="rounded-[14px] bg-black px-6 py-6 text-white">
                <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-white/70">Kalender Akademik</p>
                <div class="mt-8 space-y-6">
                    @foreach ([
                        ['day' => '20', 'month' => 'OKT', 'event' => 'Batas Input Nilai UTS'],
                        ['day' => '25', 'month' => 'OKT', 'event' => 'Rapat Koordinasi Guru'],
                        ['day' => '10', 'month' => 'NOV', 'event' => 'Hari Pahlawan (Libur)'],
                    ] as $event)
                        <div class="flex gap-4">
                            <div class="w-[48px] flex-none text-center">
                                <div class="text-[36px] font-black leading-none tracking-[-0.06em]">{{ $event['day'] }}</div>
                                <div class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] text-white/60">{{ $event['month'] }}</div>
                            </div>
                            <p class="max-w-[150px] self-center text-[14px] font-medium leading-5 text-white/90">{{ $event['event'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[14px] bg-[#e9ebef] p-5 ring-1 ring-black/5">
                <div class="flex h-full flex-col">
                    <div class="mb-4 h-[190px] rounded-[22px] bg-[#d6dbe3] relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-[1px] w-full rotate-[11deg] bg-black/10"></div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-[1px] w-full -rotate-[11deg] bg-black/10"></div>
                        </div>
                    </div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#6d6d6d]">Panduan Pengisian</p>
                    <h3 class="mt-2 text-[24px] font-extrabold tracking-[-0.04em] text-[#262626]">Modul Rapor K-Merdeka</h3>
                </div>
            </section>

            <section class="rounded-[14px] bg-[#e9ebef] p-6 ring-1 ring-black/5">
                <div class="flex h-full flex-col justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-[10px] bg-white/70 text-[#202020]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-[24px] font-extrabold tracking-[-0.04em] text-[#262626]">Template Legger</h3>
                        <p class="mt-3 max-w-[200px] text-[14px] leading-6 text-[#666]">
                            Unduh format Excel untuk rekapitulasi nilai manual.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-walikelas-shell>
