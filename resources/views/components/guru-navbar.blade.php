{{-- ============================================================
     Komponen: GuruNavbar
     Deskripsi: Sidebar navigasi untuk halaman Guru Mata Pelajaran.
     Props: $active — menu yang sedang aktif

     Komponen ini di-extract dari guru-shell.blade.php agar
     kode sidebar tidak perlu ditulis ulang di setiap halaman.
     Digunakan oleh: layouts/guru.blade.php
     ============================================================ --}}

@php
    // Ambil data user yang sedang login untuk ditampilkan di sidebar
    $user = auth()->user();
    $initials = collect(explode(' ', trim($user->name ?? 'SIRAPI')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    // Daftar menu navigasi guru mata pelajaran
    $menus = [
        ['key' => 'dashboard',          'label' => 'Dashboard',          'href' => route('dashboard'),          'icon' => 'dashboard'],
        ['key' => 'jadwal-mengajar',    'label' => 'Jadwal Mengajar',    'href' => route('jadwal'),             'icon' => 'calendar'],
        ['key' => 'data-siswa',         'label' => 'Data Siswa',         'href' => route('siswa'),              'icon' => 'users'],
        ['key' => 'penilaian',          'label' => 'Penilaian',          'href' => route('penilaian'),          'icon' => 'star'],
        ['key' => 'capaian-kompetensi', 'label' => 'Capaian Kompetensi','href' => route('capaian-kompetensi'), 'icon' => 'file-text'],
        ['key' => 'laporan-nilai',      'label' => 'Laporan Nilai',      'href' => route('laporan-nilai'),      'icon' => 'chart'],
        ['key' => 'rekap-nilai',        'label' => 'Rekap Nilai Kelas',  'href' => route('rekap-nilai'),        'icon' => 'check-square'],
        ['key' => 'profil',             'label' => 'Profil Saya',        'href' => route('profil'),             'icon' => 'user'],
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
        <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#3b82f6]">Guru Mata Pelajaran</p>
    </div>

    {{-- Badge role --}}
    <div class="mx-3 mt-3 rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2">
        <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">Panel Guru</p>
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
                    @elseif ($menu['icon'] === 'star')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m12 3 2.8 5.67 6.26.91-4.53 4.42 1.07 6.25L12 17.27 6.4 20.25l1.07-6.25L2.94 9.58l6.26-.91L12 3Z" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'check-square')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"></rect>
                            <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'chart')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M18 20V10M12 20V4M6 20v-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    @elseif ($menu['icon'] === 'user')
                        <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
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

    {{-- Profil user di bagian bawah sidebar --}}
    <div class="shrink-0 border-t border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
        <a href="{{ route('profil') }}" class="mb-2.5 flex items-center gap-3 rounded-lg p-1 -m-1 transition hover:bg-[#f1f5f9]">
            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white">
                {{ $initials }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-[12px] font-bold text-[#0f172a]">{{ $user->name }}</p>
                <p class="text-[9px] font-semibold uppercase tracking-[0.12em] text-[#64748b]">Guru Mata Pelajaran</p>
            </div>
        </a>
        {{-- Tombol logout (trigger modal di layout) --}}
        <button @click="logoutModalOpen = true" class="flex h-[34px] w-full items-center justify-center rounded bg-[#1d4ed8] text-[11px] font-bold uppercase tracking-wider text-white transition hover:bg-[#2563eb]" type="button">
            Keluar
        </button>
    </div>
</aside>
