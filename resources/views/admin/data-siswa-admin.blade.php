<x-admin-shell
    :user="auth()->user()"
    active="data-siswa"
    title="Data Siswa"
    subtitle="Pengelolaan data induk siswa"
>
    <div class="space-y-6">

        {{-- ─── HEADING ─────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Data Siswa</h1>
                <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">
                    Pengelolaan data induk siswa, verifikasi NISN, dan monitoring status aktif akademik.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Export CSV
                </button>
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#0f172a] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e293b]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                    Tambah Siswa
                </button>
            </div>
        </div>

        {{-- ─── STAT CARDS ──────────────────────────────────── --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['label' => 'Total Siswa', 'value' => '1,248'],
                ['label' => 'Siswa Aktif', 'value' => '1,202'],
                ['label' => 'Status Cuti', 'value' => '34'],
                ['label' => 'Mengundurkan Diri', 'value' => '12'],
            ] as $stat)
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ─── FILTERS ─────────────────────────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <select class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]">
                    <option>Semua Kelas</option>
                    <option>X - MIPA 1</option>
                    <option>XI - MIPA 1</option>
                    <option>XII - MIPA 1</option>
                    <option>XI - IIS 2</option>
                </select>
                <select class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]">
                    <option>Semua Status</option>
                    <option>Aktif</option>
                    <option>Cuti</option>
                    <option>Keluar</option>
                </select>
            </div>
            <p class="text-[12px] font-semibold text-[#64748b]">Menampilkan 1-10 dari 1,248 siswa</p>
        </div>

        {{-- ─── TABLE ───────────────────────────────────────── --}}
        <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="w-10 px-4 py-3"><input type="checkbox" class="rounded border-[#cbd5e1] cursor-pointer"></th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Siswa</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jenis Kelamin</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['name' => 'Ahmad Fauzi', 'nisn' => '0061263844', 'kelas' => 'XII - MIPA 1', 'gender' => 'Laki-laki', 'status' => 'AKTIF'],
                        ['name' => 'Siti Aminah', 'nisn' => '0061263721', 'kelas' => 'XII - MIPA 1', 'gender' => 'Perempuan', 'status' => 'AKTIF'],
                        ['name' => 'Budi Darmawan', 'nisn' => '0051284118', 'kelas' => 'XI - IIS 2', 'gender' => 'Laki-laki', 'status' => 'LEAVE'],
                        ['name' => 'Rina Maharani', 'nisn' => '0061344532', 'kelas' => 'X - MIPA 3', 'gender' => 'Perempuan', 'status' => 'AKTIF'],
                    ] as $siswa)
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-4 py-3.5"><input type="checkbox" class="rounded border-[#cbd5e1] cursor-pointer"></td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]">{{ strtoupper(substr($siswa['name'], 0, 1)) }}</div>
                                    <span class="font-bold text-[#0f172a]">{{ $siswa['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5"><span class="rounded bg-[#fef3c7] px-2 py-0.5 font-mono text-[12px] font-semibold text-[#92400e]">{{ $siswa['nisn'] }}</span></td>
                            <td class="px-4 py-3.5 font-semibold text-[#0f172a]">{{ $siswa['kelas'] }}</td>
                            <td class="px-4 py-3.5 text-[#475569]">{{ $siswa['gender'] }}</td>
                            <td class="px-4 py-3.5">
                                @if ($siswa['status'] === 'AKTIF')
                                    <span class="inline-flex items-center gap-1 rounded-md border border-[#a7f3d0] bg-[#ecfdf5] px-2 py-0.5 text-[10px] font-bold text-[#059669]">{{ $siswa['status'] }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md border border-[#fed7aa] bg-[#fff7ed] px-2 py-0.5 text-[10px] font-bold text-[#ea580c]">{{ $siswa['status'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <button class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"></circle><circle cx="12" cy="12" r="1.5"></circle><circle cx="12" cy="19" r="1.5"></circle></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
                <div class="flex items-center gap-2 text-[12px] text-[#64748b]">
                    <span>Baris per halaman:</span>
                    <select class="h-[30px] appearance-none rounded border border-[#e2e8f0] bg-white px-2 text-[12px] font-medium outline-none">
                        <option>10</option><option>25</option><option>50</option>
                    </select>
                </div>
                <div class="flex items-center gap-1">
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1d4ed8] text-[12px] font-bold text-white">1</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">2</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">3</button>
                    <span class="px-1 text-[#94a3b8]">...</span>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">125</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                </div>
            </div>
        </div>

        {{-- ─── BOTTOM INFO CARDS ───────────────────────────── --}}
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach ([
                ['title' => 'Verifikasi Data Siswa', 'desc' => 'Pastikan semua NISN sudah terverifikasi dengan pangkalan data kemdikbud (DAPODIK). Data yang belum terverifikasi akan ditandai dengan ikon peringatan pada kolom aksi.'],
                ['title' => 'Analisis Pertumbuhan', 'desc' => 'Peningkatan pendaftaran tahun ini mencapai 12% dibandingkan tahun ajaran 2022/2023.'],
                ['title' => 'Riwayat Perubahan', 'desc' => 'Setiap perubahan data siswa dicatat dalam log sistem untuk audit administrasi.'],
            ] as $info)
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                    <h4 class="text-[14px] font-bold text-[#0f172a]">{{ $info['title'] }}</h4>
                    <p class="mt-2 text-[12px] leading-[1.8] text-[#64748b]">{{ $info['desc'] }}</p>
                </div>
            @endforeach
        </div>

    </div>
</x-admin-shell>
