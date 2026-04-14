<x-admin-shell
    :user="auth()->user()"
    active="manajemen-user"
    title="Manajemen User"
    subtitle="Kelola pengguna sistem"
>
    <div class="space-y-6">

        {{-- ─── HEADING ─────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Manajemen User</h1>
                <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">
                    Kelola data pengguna sistem, atur peran (roles), dan pantau aktivitas akses untuk menjaga keamanan data akademik.
                </p>
            </div>
            <button class="flex h-[44px] items-center gap-2 rounded-[8px] bg-[#0f172a] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e293b]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke-width="2" stroke-linecap="round"></path><circle cx="8.5" cy="7" r="4" stroke-width="2"></circle><path d="M20 8v6m3-3h-6" stroke-width="2" stroke-linecap="round"></path></svg>
                Tambah User Baru
            </button>
        </div>

        {{-- ─── STAT CARDS ──────────────────────────────────── --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Pengguna</p>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-[40px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">1,284</span>
                    <span class="pb-1 text-[13px] font-bold text-[#059669]">+12%</span>
                </div>
            </div>
            @foreach ([
                ['icon' => 'shield', 'label' => 'Admin Sistem', 'value' => '12'],
                ['icon' => 'user', 'label' => 'Guru', 'value' => '86'],
                ['icon' => 'users', 'label' => 'Siswa & Alumni', 'value' => '1,186'],
            ] as $stat)
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                    <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-[6px] bg-[#f1f5f9] text-[#64748b]">
                        @if ($stat['icon'] === 'shield')
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        @elseif ($stat['icon'] === 'user')
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        @else
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-width="2"></path><circle cx="9.5" cy="7" r="3" stroke-width="2"></circle><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 4.13a3 3 0 0 1 0 5.74" stroke-linecap="round" stroke-width="2"></path></svg>
                        @endif
                    </div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-[28px] font-black leading-none tracking-[-0.04em] text-[#0f172a]">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ─── TOOLBAR ─────────────────────────────────────── --}}
        <div class="flex flex-wrap items-center gap-3">
            <button class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[13px] font-semibold text-[#334155] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M11 12h4" stroke-width="2" stroke-linecap="round"></path></svg>
                Filter Peran
            </button>
            <button class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[13px] font-semibold text-[#334155] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                Urutkan
            </button>
            <div class="relative ml-auto">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"></circle><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path></svg>
                <input class="h-[38px] w-[280px] rounded-[8px] border border-[#e2e8f0] bg-white pl-10 pr-4 text-[13px] text-[#334155] placeholder-[#94a3b8] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Cari nama, email, atau NIP/NISN..." type="text">
            </div>
        </div>

        {{-- ─── TABLE ───────────────────────────────────────── --}}
        <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">User</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Identitas</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Role / Peran</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['name' => 'Budi Santoso', 'email' => 'budi.tu@sirapi.sch.id', 'id' => 'NIP: 19850312...', 'roles' => ['ADMIN TU', 'OPERATOR'], 'status' => 'Aktif'],
                        ['name' => 'Siti Aminah', 'email' => 'siti.guru@sirapi.sch.id', 'id' => 'NUPTK: 783100...', 'roles' => ['GURU MAPEL', 'WALI KELAS'], 'status' => 'Aktif'],
                        ['name' => 'Andi Wijaya', 'email' => 'andi.std@sirapi.sch.id', 'id' => 'NISN: 0056123...', 'roles' => ['SISWA'], 'status' => 'Nonaktif'],
                    ] as $u)
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]">
                                        {{ strtoupper(substr($u['name'], 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-[#0f172a]">{{ $u['name'] }}</p>
                                        <p class="text-[11px] text-[#64748b]">{{ $u['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-mono text-[12px] text-[#64748b]">{{ $u['id'] }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($u['roles'] as $role)
                                        <span class="rounded bg-[#0f172a] px-2 py-0.5 text-[10px] font-bold text-white">{{ $role }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full {{ $u['status'] === 'Aktif' ? 'bg-[#059669]' : 'bg-[#94a3b8]' }}"></span>
                                    <span class="text-[12px] font-medium {{ $u['status'] === 'Aktif' ? 'text-[#059669]' : 'text-[#94a3b8]' }}">{{ $u['status'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <button class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
                <p class="text-[12px] font-semibold text-[#64748b]">Menampilkan 1-10 dari 1,284 User</p>
                <div class="flex items-center gap-1">
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1d4ed8] text-[12px] font-bold text-white">1</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">2</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">3</button>
                    <span class="px-1 text-[#94a3b8]">...</span>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-semibold text-[#64748b] hover:bg-[#f1f5f9]">129</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                </div>
            </div>
        </div>

        {{-- ─── INFO CARD ───────────────────────────────────── --}}
        <div class="rounded-[14px] border border-[#e2e8f0] bg-[#f8fafc] p-6">
            <div class="flex gap-4">
                <div class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-[#dbeafe] text-[#1d4ed8]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 16v-4m0-4h.01" stroke-width="2" stroke-linecap="round"></path></svg>
                </div>
                <div>
                    <h4 class="text-[16px] font-bold text-[#0f172a]">Panduan Pengaturan Multi-Role</h4>
                    <p class="mt-1 max-w-[640px] text-[13px] leading-[1.8] text-[#475569]">
                        Setiap user dapat memiliki lebih dari satu peran (role). Contohnya, seorang Guru dapat merangkap sebagai Operator Akademik atau Admin Perpustakaan.
                        Klik tombol <span class="inline-flex items-center gap-1 rounded bg-[#0f172a] px-2 py-0.5 text-[10px] font-bold text-white">EDIT PERAN</span> pada baris user untuk menambahkan atau menghapus akses mereka.
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-admin-shell>
