<x-admin-shell
    :user="auth()->user()"
    active="akademik"
    title="Manajemen Akademik"
    subtitle="Pengelolaan tahun ajaran dan kelas"
>
    <div class="space-y-6">

        {{-- ─── TAHUN AJARAN ────────────────────────────────── --}}
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_280px]">
            <div class="space-y-5">
                {{-- Header --}}
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a]">Tahun Ajaran 2023/2024</h1>
                        <p class="mt-1 text-[14px] text-[#475569]">Linimasa kegiatan akademik berjalan.</p>
                    </div>
                    <button class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[12px] font-bold uppercase tracking-[0.08em] text-[#334155] transition hover:bg-[#f1f5f9]">Ubah Periode</button>
                </div>

                {{-- Timeline --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e2e8f0]">
                                <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"></rect><path d="M16 2v4M8 2v4M3 10h18" stroke-width="2" stroke-linecap="round"></path></svg>
                            </div>
                            <span class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">JUL '23</span>
                        </div>
                        <div class="mx-4 h-[3px] flex-1 rounded-full bg-[#e2e8f0]">
                            <div class="h-full w-[65%] rounded-full bg-[#0f172a]"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">JUNI '24</span>
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e2e8f0]">
                                <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Semester cards --}}
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] p-4">
                            <p class="text-[13px] font-bold text-[#0f172a]">Semester Ganjil</p>
                            <p class="mt-0.5 text-[12px] font-semibold text-[#475569]">Status: Selesai</p>
                            <p class="mt-1 text-[11px] text-[#94a3b8]">17 Juli - 22 Des 2023</p>
                        </div>
                        <div class="rounded-[10px] border border-[#1d4ed8] bg-[#eff6ff] p-4">
                            <p class="text-[13px] font-bold text-[#1d4ed8]">Semester Genap</p>
                            <p class="mt-0.5 text-[12px] font-semibold text-[#475569]">Status: Aktif</p>
                            <p class="mt-1 text-[11px] text-[#94a3b8]">08 Jan - 21 Juni 2024</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Semester --}}
            <div class="space-y-3">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Detail Semester</p>
                <div class="rounded-[10px] border border-[#e2e8f0] bg-white p-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Minggu Berjalan</p>
                    <p class="mt-1"><span class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">14</span> <span class="text-[14px] font-medium text-[#64748b]">dari 20</span></p>
                </div>
                <div class="rounded-[10px] border border-[#e2e8f0] bg-white p-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Target Kurikulum</p>
                    <div class="mt-2 h-[6px] overflow-hidden rounded-full bg-[#e2e8f0]">
                        <div class="h-full w-[78%] rounded-full bg-[#1d4ed8]"></div>
                    </div>
                    <p class="mt-1 text-right text-[12px] font-bold text-[#1d4ed8]">78%</p>
                </div>
                <div class="rounded-[10px] bg-[#0f172a] p-4 text-white">
                    <div class="flex items-center justify-between">
                        <svg class="h-5 w-5 text-[#60a5fa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <span class="rounded bg-white/20 px-2 py-0.5 text-[9px] font-bold uppercase">Penting</span>
                    </div>
                    <p class="mt-3 text-[13px] leading-[1.6] font-medium">Pengisian Rapor Semester Genap dimulai dalam 12 hari.</p>
                </div>
            </div>
        </div>

        {{-- ─── PLOTTING SISWA & KELAS ──────────────────────── --}}
        <div>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-[22px] font-black tracking-[-0.04em] text-[#0f172a]">Plotting Siswa & Kelas</h2>
                    <p class="mt-1 text-[13px] text-[#475569]">Distribusi kapasitas dan pembagian rombongan belajar.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[12px] font-bold text-[#334155] transition hover:bg-[#f1f5f9]">Atur Kapasitas</button>
                    <button class="flex h-[38px] items-center gap-2 rounded-[8px] bg-[#0f172a] px-4 text-[12px] font-bold text-white transition hover:bg-[#1e293b]">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                        Plotting Otomatis
                    </button>
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['kelas' => 'X-A', 'jurusan' => 'SAINTEK / MIPA', 'kapasitas' => '34 / 36', 'pct' => 94, 'wali' => 'Wali Kelas: Drs. Budi Santoso'],
                    ['kelas' => 'X-B', 'jurusan' => 'SAINTEK / MIPA', 'kapasitas' => '36 / 36', 'pct' => 100, 'wali' => 'KAPASITAS PENUH'],
                    ['kelas' => 'X-C', 'jurusan' => 'SOSHUM / IPS', 'kapasitas' => '28 / 36', 'pct' => 78, 'wali' => 'Wali Kelas: Siti Aminah, S.Pd'],
                ] as $kelas)
                    <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                        <div class="mb-3 flex h-8 w-8 items-center justify-center rounded-[6px] bg-[#f1f5f9]">
                            <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                        <p class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a]">{{ $kelas['kelas'] }}</p>
                        <p class="text-[11px] font-semibold text-[#64748b]">{{ $kelas['jurusan'] }}</p>
                        <div class="mt-3 flex items-center justify-between text-[12px]">
                            <span class="font-semibold text-[#334155]">Kapasitas</span>
                            <span class="font-bold text-[#0f172a]">{{ $kelas['kapasitas'] }}</span>
                        </div>
                        <div class="mt-1.5 h-[4px] overflow-hidden rounded-full bg-[#e2e8f0]">
                            <div class="h-full rounded-full {{ $kelas['pct'] >= 100 ? 'bg-[#dc2626]' : 'bg-[#1d4ed8]' }}" style="width: {{ min($kelas['pct'], 100) }}%"></div>
                        </div>
                        <p class="mt-2 text-[11px] {{ $kelas['pct'] >= 100 ? 'font-bold text-[#dc2626]' : 'text-[#64748b]' }}">{{ $kelas['wali'] }}</p>
                    </div>
                @endforeach

                {{-- Tambah Kelas Baru --}}
                <div class="flex flex-col items-center justify-center rounded-[14px] border-2 border-dashed border-[#cbd5e1] bg-[#f8fafc] p-5 transition hover:border-[#3b82f6] cursor-pointer">
                    <div class="flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#e2e8f0] text-[#64748b]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                    </div>
                    <p class="mt-2 text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">Tambah Kelas Baru</p>
                </div>
            </div>
        </div>

        {{-- ─── ANTREAN PLOTTING SISWA ──────────────────────── --}}
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Antrean Plotting Siswa</p>
                    <p class="mt-0.5 text-[13px] text-[#475569]">Siswa baru atau pindahan yang belum memiliki kelas.</p>
                </div>
                <span class="rounded bg-[#dc2626] px-2.5 py-1 text-[10px] font-bold text-white">14 SISWA</span>
            </div>

            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Gender</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peminatan</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['init' => 'AD', 'name' => 'Aditya Pratama', 'nisn' => '0092182711', 'gender' => 'Laki-laki', 'peminatan' => 'SAINTEK'],
                        ['init' => 'BP', 'name' => 'Bella Puspita', 'nisn' => '0092182744', 'gender' => 'Perempuan', 'peminatan' => 'SOSHUM'],
                        ['init' => 'DR', 'name' => 'Deni Ramadhan', 'nisn' => '0092182788', 'gender' => 'Laki-laki', 'peminatan' => 'SAINTEK'],
                    ] as $siswa)
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 flex-none items-center justify-center rounded-[6px] bg-[#eff6ff] text-[10px] font-bold text-[#1d4ed8]">{{ $siswa['init'] }}</div>
                                    <span class="font-bold text-[#0f172a]">{{ $siswa['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-[12px] text-[#64748b]">{{ $siswa['nisn'] }}</td>
                            <td class="px-4 py-3.5 text-[#475569]">{{ $siswa['gender'] }}</td>
                            <td class="px-4 py-3.5"><span class="rounded bg-[#0f172a] px-2 py-0.5 text-[10px] font-bold text-white">{{ $siswa['peminatan'] }}</span></td>
                            <td class="px-4 py-3.5">
                                <button class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t border-[#e2e8f0] px-6 py-3 text-center">
                <button class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#1d4ed8] transition hover:text-[#1e40af]">Lihat Semua Antrean (11 Lainnya)</button>
            </div>
        </div>

    </div>
</x-admin-shell>
