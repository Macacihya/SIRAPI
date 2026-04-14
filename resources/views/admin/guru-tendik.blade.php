<x-admin-shell
    :user="auth()->user()"
    active="guru"
    title="Guru"
    subtitle="Database Kepegawaian"
>
    <div class="space-y-6">

        {{-- ─── HEADING ─────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Database Kepegawaian</p>
                <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Guru</h1>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Export Data
                </button>
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#0f172a] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e293b]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                    Tambah Data Baru
                </button>
            </div>
        </div>

        {{-- ─── STAT CARDS ──────────────────────────────────── --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Guru</p>
                <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">48</p>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Wali Kelas</p>
                <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">24</p>
            </div>
        </div>

        {{-- ─── SEARCH + FILTERS ────────────────────────────── --}}
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[240px]">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"></circle><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path></svg>
                <input class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white pl-10 pr-4 text-[13px] text-[#334155] placeholder-[#94a3b8] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Cari berdasarkan nama atau NIP..." type="text">
            </div>
            <select class="h-[42px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]">
                <option>Semua Peran</option>
                <option>Guru Mapel</option>
                <option>Wali Kelas</option>
            </select>
            <select class="h-[42px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]">
                <option>Status Aktif</option>
                <option>Nonaktif</option>
            </select>
        </div>

        {{-- ─── TABLE ───────────────────────────────────────── --}}
        <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Identitas Pegawai</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran / Jabatan</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['name' => 'Drs. Ahmad Subagja, M.Pd.', 'email' => 'ahmad.subagja@school.id', 'nip' => '197503122005011880', 'roles' => ['WALI KELAS XI IPA 1'], 'role_type' => 'wali', 'mapel' => 'Matematika Wajib'],
                        ['name' => 'Siti Rahmawati, S.Pd.', 'email' => 'siti.rahma@school.id', 'nip' => '198011852014012001', 'roles' => ['GURU MAPEL'], 'role_type' => 'guru', 'mapel' => 'Bahasa Inggris'],
                        ['name' => 'Bambang Wijaya', 'email' => 'b.wijaya@school.id', 'nip' => '6-7822981112', 'roles' => ['GURU MAPEL'], 'role_type' => 'guru', 'mapel' => 'N/A'],
                        ['name' => 'Rina Permata, M.Si.', 'email' => 'rina.p@school.id', 'nip' => '198205122018012005', 'roles' => ['WALI KELAS XI IPS 2'], 'role_type' => 'wali', 'mapel' => 'Sosiologi / Sejarah'],
                    ] as $guru)
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]">
                                        {{ strtoupper(substr($guru['name'], 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-[#0f172a]">{{ $guru['name'] }}</p>
                                        <p class="text-[11px] text-[#64748b]">{{ $guru['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="rounded bg-[#fef3c7] px-2 py-0.5 font-mono text-[12px] font-semibold text-[#92400e]">{{ $guru['nip'] }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($guru['roles'] as $role)
                                        <span class="rounded bg-[#0f172a] px-2 py-0.5 text-[10px] font-bold text-white">{{ $role }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-4 text-[13px] text-[#475569]">{{ $guru['mapel'] }}</td>
                            <td class="px-4 py-4">
                                <button class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
                <p class="text-[12px] font-semibold text-[#64748b]">Menampilkan 1-10 dari 60 entri</p>
                <div class="flex items-center gap-1">
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1d4ed8] text-[12px] font-bold text-white">1</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">2</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">3</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                </div>
            </div>
        </div>

    </div>
</x-admin-shell>
