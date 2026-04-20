@props([
    'title' => 'Dashboard Utama',
    'subtitle' => null,
    'active' => 'dashboard',
    'user' => auth()->user(),
])

@php
    $user = $user ?? auth()->user();
    $initials = collect(explode(' ', trim($user->name ?? 'SIRAPI')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    $menus = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'href' => route('guru.dashboard'),
            'icon' => 'dashboard',
        ],
        [
            'key' => 'jadwal-mengajar',
            'label' => 'Jadwal Mengajar',
            'href' => route('guru.jadwal-mengajar'),
            'icon' => 'calendar',
        ],
        [
            'key' => 'data-siswa',
            'label' => 'Data Siswa',
            'href' => route('guru.data-siswa'),
            'icon' => 'users',
        ],
        [
            'key' => 'penilaian',
            'label' => 'Penilaian',
            'href' => route('guru.penilaian'),
            'icon' => 'star',
        ],
        [
            'key' => 'capaian-kompetensi',
            'label' => 'Capaian Kompetensi',
            'href' => route('guru.capaian-kompetensi'),
            'icon' => 'file-text',
        ],
        [
            'key' => 'laporan-nilai',
            'label' => 'Laporan Nilai',
            'href' => route('guru.laporan-nilai'),
            'icon' => 'file-text',
        ],
        [
            'key' => 'rekap-nilai',
            'label' => 'Rekap Nilai Kelas',
            'href' => route('guru.rekap-nilai'),
            'icon' => 'check-square',
        ]
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $title }} - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .sidebar-scrollbar::-webkit-scrollbar { width: 4px; }
        .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen bg-[#f0f4f8] text-[#1e293b]" x-data="{ sidebarOpen: false, logoutModalOpen: false }">

    {{-- ─── BACKDROP (Mobile/Tablet only) ─────────────────────── --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden"
        style="display: none;"
    ></div>

    {{-- ─── SIDEBAR ───────────────────────────────────────────── --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-[240px] flex-col border-r border-[#e2e8f0] bg-white/95 backdrop-blur shadow-xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:shadow-none"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        {{-- Brand --}}
        <div class="flex min-h-[64px] flex-col justify-center border-b border-[#e2e8f0] px-5">
            <h1 class="text-[20px] font-black uppercase tracking-[-0.06em] text-[#0f172a]">SIRAPI</h1>
            <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#3b82f6]">Sistem Rapor Pintar</p>
        </div>

        {{-- Navigation (scrollable) --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 sidebar-scrollbar">
            <div class="flex flex-col gap-1">
                @foreach ($menus as $menu)
                    @php $isActive = $active === $menu['key']; @endphp
                    <a
                        href="{{ $menu['href'] }}"
                        class="{{ $isActive ? 'bg-[#eff6ff] text-[#1d4ed8] shadow-[inset_-4px_0_0_0_#3b82f6]' : 'text-[#64748b] hover:bg-[#f1f5f9] hover:text-[#0f172a]' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-medium transition"
                    >
                        @if ($menu['icon'] === 'dashboard')
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                            </svg>
                        @elseif ($menu['icon'] === 'home')
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 11.5 12 4l9 7.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                <path d="M5.5 10.5V20h13V10.5" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        @elseif ($menu['icon'] === 'calendar')
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"></rect>
                                <path d="M16 3v4M8 3v4M3 10h18" stroke-linecap="round" stroke-width="2"></path>
                            </svg>
                        @elseif ($menu['icon'] === 'users')
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-width="2"></path>
                                <circle cx="9.5" cy="7" r="3" stroke-width="2"></circle>
                                <path d="M20 21v-2a4 4 0 0 0-3-3.87" stroke-linecap="round" stroke-width="2"></path>
                                <path d="M16 4.13a3 3 0 0 1 0 5.74" stroke-linecap="round" stroke-width="2"></path>
                            </svg>
                        @elseif ($menu['icon'] === 'check-square')
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"></rect>
                                <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        @elseif ($menu['icon'] === 'star')
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="m12 3 2.8 5.67 6.26.91-4.53 4.42 1.07 6.25L12 17.27 6.4 20.25l1.07-6.25L2.94 9.58l6.26-.91L12 3Z" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        @else
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-linejoin="round" stroke-width="2"></path>
                                <path d="M14 2v6h6M8 13h8M8 17h5M8 9h3" stroke-linecap="round" stroke-width="2"></path>
                            </svg>
                        @endif
                        <span class="leading-tight">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        {{-- User Profile (pinned to bottom) --}}
        <div class="shrink-0 border-t border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
            <a href="{{ route('guru.profil') }}" class="mb-2.5 flex items-center gap-3 rounded-lg p-1 -m-1 transition hover:bg-[#f1f5f9]">
                <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-[#f1f5f9] text-[#94a3b8]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-[12px] font-bold text-[#0f172a]">{{ $user->name ?? 'Drs. M. Taufik' }}</p>
                    <p class="text-[9px] font-semibold uppercase tracking-[0.12em] text-[#64748b]">Guru Mata Pelajaran</p>
                </div>
            </a>
            <button @click="logoutModalOpen = true" class="flex h-[34px] w-full items-center justify-center rounded bg-[#1d4ed8] text-[11px] font-bold uppercase tracking-wider text-white transition hover:bg-[#2563eb]" type="button">
                Keluar
            </button>
        </div>
    </aside>

    {{-- ─── MAIN WRAPPER ──────────────────────────────────────── --}}
    <div class="flex min-h-screen flex-col lg:ml-[240px]">

        {{-- ─── HEADER ────────────────────────────────────────── --}}
        <header
            class="fixed top-0 right-0 left-0 z-30 flex h-[64px] items-center border-b border-[#e2e8f0] bg-white/95 px-4 backdrop-blur shadow-sm sm:px-6 lg:left-[240px] lg:px-8"
        >
            {{-- Left: Hamburger + Title --}}
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="flex h-9 w-9 flex-none items-center justify-center rounded-lg border border-[#cbd5e1] text-[#475569] transition hover:bg-[#f1f5f9] hover:text-[#0f172a] lg:hidden"
                    aria-label="Toggle Sidebar"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarOpen" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        <path x-show="sidebarOpen" d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="display: none;"></path>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h2 class="truncate text-[16px] font-extrabold tracking-tight text-[#0f172a] sm:text-[20px] lg:text-[24px]">{{ $title }}</h2>
                    @if ($subtitle)
                        <p class="hidden text-[11px] text-[#64748b] sm:block">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>

            {{-- Right: Notification + User --}}
            <div class="flex items-center gap-2 sm:gap-3">
                {{-- User info (hidden on very small screens) --}}
                <a href="{{ route('guru.profil') }}" class="hidden items-center gap-2.5 sm:flex rounded-lg p-1.5 -m-1.5 transition hover:bg-[#f1f5f9]">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#1e293b]">{{ $user->name ?? 'Drs. M. Taufik' }}</p>
                        <p class="text-[10px] text-[#64748b]">NIP. {{ $user->nip ?? '197003121995031002' }}</p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#f1f5f9] text-[#94a3b8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </a>

                {{-- Avatar only (visible on small screens) --}}
                <a href="{{ route('guru.profil') }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#f1f5f9] text-[#94a3b8] sm:hidden transition hover:bg-[#e2e8f0]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </header>

        {{-- ─── MAIN CONTENT ──────────────────────────────────── --}}
        <main class="flex-1 px-4 pt-[80px] pb-6 sm:px-6 sm:pt-[88px] lg:px-10 lg:pt-[96px]">
            {{ $slot }}
        </main>
    </div>

    {{-- ─── LOGOUT MODAL ────────────────────────────────────────── --}}
    <div x-show="logoutModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="logoutModalOpen = false">
        <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] mb-4 ring-4 ring-[#fee2e2]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                <h3 class="text-[18px] font-black text-[#0f172a]">Konfirmasi Keluar</h3>
                <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Apakah Anda yakin ingin keluar dari sesi panel Guru ini?</p>
            </div>
            <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                <button @click="logoutModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full rounded-lg bg-[#dc2626] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#b91c1c]">Ya, Keluar</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
