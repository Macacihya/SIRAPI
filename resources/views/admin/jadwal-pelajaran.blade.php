<x-admin-shell
    :user="auth()->user()"
    active="jadwal-pelajaran"
    title="Jadwal Pelajaran"
    subtitle="Manajemen alokasi waktu dan ruang kelas"
>
    <div class="space-y-6">

        {{-- ─── HEADING ─────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Jadwal Pelajaran</h1>
                <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">
                    Manajemen alokasi waktu, guru, dan ruang kelas secara komprehensif. Sesuaikan jadwal mingguan dengan presisi arsitektural.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Ekspor PDF
                </button>
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#0f172a] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e293b]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                    Tambah Jadwal
                </button>
            </div>
        </div>

        {{-- ─── FILTER BAR ──────────────────────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tingkat Kelas</label>
                    <select class="mt-1 block h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]">
                        <option>Kelas X -</option>
                        <option>Kelas XI</option>
                        <option>Kelas XII</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Semester</label>
                    <select class="mt-1 block h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]">
                        <option>Ganj</option>
                        <option>Genap</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center rounded-[8px] border border-[#e2e8f0] bg-white overflow-hidden">
                <button class="h-[36px] px-4 text-[12px] font-bold bg-[#0f172a] text-white">Mingguan</button>
                <button class="h-[36px] px-4 text-[12px] font-bold text-[#64748b] hover:bg-[#f1f5f9]">Harian</button>
            </div>
        </div>

        {{-- ─── SCHEDULE GRID ───────────────────────────────── --}}
        <div class="overflow-x-auto rounded-[14px] border border-[#e2e8f0] bg-white">
            <table class="w-full text-[12px]">
                <thead>
                    <tr class="bg-[#f8fafc]">
                        <th class="w-[80px] border-r border-[#e2e8f0] px-3 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]"></th>
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                            <th class="border-r border-[#e2e8f0] px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b] last:border-r-0">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{-- Slot 1: 07:00 --}}
                    <tr class="border-t border-[#e2e8f0]">
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="text-[13px] font-bold text-[#0f172a]">07:00</p>
                            <p class="text-[10px] text-[#94a3b8]">08:30</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Matematika Wajib</p>
                            <p class="text-[11px] text-[#64748b]">Drs. Bambang Wijaya</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 Lab 02</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Bahasa Indonesia</p>
                            <p class="text-[11px] text-[#64748b]">Siti Aminah, M.Pd</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 R. 104</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Fisika Terapan</p>
                            <p class="text-[11px] text-[#64748b]">Ir. Hendra Gunawan</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 Lab Fisika</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Ekonomi</p>
                            <p class="text-[11px] text-[#64748b]">Rina Sari, S.E</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 R. 201</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Olahraga</p>
                            <p class="text-[11px] text-[#64748b]">Budi Setiawan</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 Lapangan Utama</p>
                        </td>
                        <td class="px-3 py-3 align-top text-center text-[#94a3b8] italic">Libur</td>
                    </tr>

                    {{-- IST --}}
                    <tr class="bg-[#f8fafc]">
                        <td class="border-r border-[#e2e8f0] px-3 py-2 text-[10px] font-bold uppercase tracking-[0.08em] text-[#94a3b8]">IST</td>
                        <td colspan="6" class="px-3 py-2 text-center text-[10px] font-bold uppercase tracking-[0.2em] text-[#94a3b8]">Istirahat & Transisi</td>
                    </tr>

                    {{-- Slot 2: 08:45 --}}
                    <tr class="border-t border-[#e2e8f0]">
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="text-[13px] font-bold text-[#0f172a]">08:45</p>
                            <p class="text-[10px] text-[#94a3b8]">10:15</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <div class="rounded bg-[#fef2f2] border border-[#fecaca] px-2 py-1">
                                <p class="text-[10px] font-bold text-[#dc2626]">KONFLIK RUANG</p>
                                <p class="text-[11px] font-semibold text-[#0f172a]">Kimia Dasar</p>
                                <p class="text-[10px] text-[#64748b]">Dr. Agus Salim</p>
                            </div>
                            <p class="mt-1 text-[10px] font-bold text-[#dc2626] cursor-pointer">SELESAIKAN</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Sejarah Indonesia</p>
                            <p class="text-[11px] text-[#64748b]">Drs. Bambang Wijaya</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 R. 104</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Matematika Wajib</p>
                            <p class="text-[11px] text-[#64748b]">Drs. Bambang Wijaya</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 Lab 02</p>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <div class="flex flex-col items-center justify-center py-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dashed border-[#cbd5e1] text-[#94a3b8]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                                </div>
                                <p class="mt-1 text-[10px] font-bold uppercase text-[#94a3b8]">Assign</p>
                            </div>
                        </td>
                        <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                            <p class="font-bold text-[#0f172a]">Sosiologi</p>
                            <p class="text-[11px] text-[#64748b]">Dewi Kusuma, S.Sos</p>
                            <p class="mt-1 text-[10px] text-[#94a3b8]">🏫 R. 302</p>
                        </td>
                        <td class="px-3 py-3 align-top text-center text-[#94a3b8] italic">Libur</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- ─── BOTTOM: STATUS + LOG ────────────────────────── --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Status Penggunaan Ruang + Guru Tersedia --}}
            <div class="space-y-6">
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <h3 class="text-[16px] font-black tracking-[-0.02em] text-[#0f172a]">Status Penggunaan Ruang</h3>
                    <div class="mt-4 space-y-4">
                        @foreach ([
                            ['name' => 'Ruang Teori (A1-A12)', 'pct' => 85],
                            ['name' => 'Laboratorium', 'pct' => 42],
                            ['name' => 'Aula & Lapangan', 'pct' => 15],
                        ] as $ruang)
                            <div>
                                <div class="flex items-center justify-between text-[12px]">
                                    <span class="font-bold uppercase text-[#0f172a]">{{ $ruang['name'] }}</span>
                                    <span class="font-bold text-[#0f172a]">{{ $ruang['pct'] }}%</span>
                                </div>
                                <div class="mt-1.5 h-[6px] overflow-hidden rounded-full bg-[#e2e8f0]">
                                    <div class="h-full rounded-full bg-[#0f172a]" style="width: {{ $ruang['pct'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Guru Tersedia Saat Ini</p>
                    <div class="mt-3 flex items-center">
                        <div class="flex -space-x-2">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-[#e2e8f0] text-[10px] font-bold text-[#475569]">{{ chr(65 + $i) }}</div>
                            @endfor
                        </div>
                        <span class="ml-2 text-[13px] font-bold text-[#64748b]">+12</span>
                    </div>
                </div>
            </div>

            {{-- Log Perubahan --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="text-[20px] font-black tracking-[-0.04em] text-[#0f172a]">Log Perubahan Terakhir</h3>
                <div class="mt-5 space-y-0 divide-y divide-[#f1f5f9]">
                    @foreach ([
                        ['title' => 'Perubahan Jadwal: Fisika Terapan', 'desc' => 'Dipindahkan dari R. 202 ke Lab Fisika oleh Admin TU-01', 'time' => '12 Menit Lalu'],
                        ['title' => 'Penugasan Guru Baru: Matematika', 'desc' => 'Drs. Bambang Wijaya ditugaskan untuk Kelas X-MIPA 1', 'time' => '1 Jam Lalu'],
                        ['title' => 'Deteksi Konflik Jadwal', 'desc' => 'Kimia Dasar dan Biologi memiliki alokasi ruang yang sama (R. 104)', 'time' => '2 Jam Lalu'],
                    ] as $log)
                        <div class="flex items-start gap-4 py-4">
                            <div class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-[13px] font-bold text-[#0f172a]">{{ $log['title'] }}</p>
                                    <span class="flex-none text-[10px] font-bold uppercase tracking-[0.08em] text-[#94a3b8]">{{ $log['time'] }}</span>
                                </div>
                                <p class="mt-1 text-[12px] leading-[1.6] text-[#64748b]">{{ $log['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</x-admin-shell>
