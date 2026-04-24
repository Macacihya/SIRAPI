{{-- ============================================================
     Komponen: AdminNavbar
     Deskripsi: Sidebar navigasi untuk halaman Admin TU.
     Props: $active — menu yang sedang aktif
     Dibuat dengan: php artisan make:component AdminNavbar

     Komponen ini di-extract dari admin-shell.blade.php agar
     kode sidebar tidak perlu ditulis ulang di setiap halaman.
     ============================================================ --}}

@php
    // Ambil data user yang sedang login untuk ditampilkan di sidebar
    $user = auth()->user();
    $initials = collect(explode(' ', trim($user->name ?? 'SIRAPI')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    // Daftar menu navigasi admin TU
    $menus = [
        ['key' => 'dashboard',        'label' => 'Dashboard',         'href' => route('dashboard'),        'icon' => 'dashboard'],
        ['key' => 'manajemen-user',   'label' => 'Manajemen User',    'href' => route('manajemen-user'),   'icon' => 'users-manage'],
        ['key' => 'data-sekolah',     'label' => 'Data Sekolah',      'href' => route('data-sekolah'),     'icon' => 'school'],
        ['key' => 'guru',             'label' => 'Guru',              'href' => route('guru-tendik'),      'icon' => 'user'],
        ['key' => 'data-siswa',       'label' => 'Data Siswa',        'href' => route('siswa'),            'icon' => 'users'],
        ['key' => 'akademik',         'label' => 'Akademik',          'href' => route('akademik'),         'icon' => 'book'],
        ['key' => 'mata-pelajaran',   'label' => 'Mata Pelajaran',    'href' => route('mata-pelajaran'),   'icon' => 'book'],
        ['key' => 'jadwal-pelajaran', 'label' => 'Jadwal Pelajaran',  'href' => route('jadwal'),           'icon' => 'calendar'],
        ['key' => 'aturan-nilai',     'label' => 'Aturan Nilai',      'href' => route('aturan-nilai'),     'icon' => 'clipboard'],
        ['key' => 'laporan-nilai',    'label' => 'Laporan Nilai',     'href' => route('laporan-nilai'),    'icon' => 'book'],
        ['key' => 'rekap-nilai',      'label' => 'Rekap Nilai',       'href' => route('rekap-nilai'),      'icon' => 'clipboard'],
    ];
@endphp

{{-- Sidebar fixed di sisi kiri (responsive: hidden di mobile, toggle via Alpine.js) --}}
<aside
    class="fixed inset-y-0 left-0 z-50 flex w-[240px] flex-col border-r border-[#e2e8f0] bg-white/95 backdrop-blur shadow-xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    {{-- Brand SIRAPI --}}
    <div class="flex min-h-[64px] flex-col justify-center border-b border-[#e2e8f0] px-5">
        <h1 class="text-[20px] font-black uppercase tracking-[-0.06em] text-[#0f172a]">SIRAPI</h1>
        <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#3b82f6]">Admin TU</p>
    </div>

    {{-- Badge tahun ajaran --}}
    <div class="mx-3 mt-3 rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2">
        <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">TU Administration</p>
        <p class="mt-0.5 text-xs font-bold text-[#1e40af]">Tahun Ajaran 2023/2024</p>
    </div>

    {{-- Menu navigasi (scrollable jika banyak) --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 sidebar-scrollbar">
        <div class="flex flex-col gap-1">
            @foreach ($menus as $menu)
                @php $isActive = $active === $menu['key']; @endphp
                <a
                    href="{{ $menu['href'] }}"
                    class="{{ $isActive ? 'bg-[#eff6ff] text-[#1d4ed8] shadow-[inset_-4px_0_0_0_#3b82f6]' : 'text-[#64748b] hover:bg-[#f1f5f9] hover:text-[#0f172a]' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-medium transition"
                >
                    {{-- Icon SVG berdasarkan key menu --}}
                    @if ($menu['icon'] === 'dashboard')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                        </svg>
                    @elseif ($menu['icon'] === 'users-manage')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0zm6-4a2 2 0 11-4 0 2 2 0 014 0zM5 8a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'school')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'user')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'users')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-width="2"></path>
                            <circle cx="9.5" cy="7" r="3" stroke-width="2"></circle>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87" stroke-linecap="round" stroke-width="2"></path>
                            <path d="M16 4.13a3 3 0 0 1 0 5.74" stroke-linecap="round" stroke-width="2"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'book')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'calendar')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"></rect>
                            <path d="M16 2v4M8 2v4M3 10h18" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                    @else
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    @endif
                    <span class="leading-tight">{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Tombol logout di bagian bawah sidebar --}}
    <div class="shrink-0 border-t border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
        <button @click="logoutModalOpen = true" class="flex h-[34px] w-full items-center justify-center rounded bg-[#1d4ed8] text-[11px] font-bold uppercase tracking-wider text-white transition hover:bg-[#2563eb]" type="button">
            Keluar
        </button>
    </div>
</aside>