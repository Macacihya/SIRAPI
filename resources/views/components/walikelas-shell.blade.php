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
            'href' => route('dashboard'),
            'icon' => 'dashboard',
        ],
        [
            'key' => 'profil-kelas',
            'label' => 'Profil Kelas',
            'href' => '#',
            'icon' => 'home',
        ],
        [
            'key' => 'jadwal-kelas',
            'label' => 'Jadwal Kelas',
            'href' => '#',
            'icon' => 'calendar',
        ],
        [
            'key' => 'data-siswa',
            'label' => 'Data Siswa Kelas',
            'href' => '#',
            'icon' => 'users',
        ],
        [
            'key' => 'kehadiran',
            'label' => 'Kehadiran Siswa',
            'href' => '#',
            'icon' => 'check-square',
        ],
        [
            'key' => 'penilaian',
            'label' => 'Penilaian Kelas',
            'href' => '#',
            'icon' => 'star',
        ],
        [
            'key' => 'rapor',
            'label' => 'Rapor Siswa',
            'href' => '#',
            'icon' => 'file-text',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $title }} - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f5f5f3] text-[#171717]">
<div class="min-h-screen lg:flex">
    <aside class="border-b border-black/5 bg-[#efefed] lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-[240px] lg:flex-col lg:border-b-0 lg:border-r">
        <div class="flex flex-1 flex-col">
            <div class="border-b border-black/5 px-5 py-6">
                <h1 class="text-[27px] font-black uppercase tracking-[-0.06em] text-[#1f1f1f]">SIRAPI</h1>
                <p class="mt-1 text-[10px] font-medium uppercase tracking-[0.22em] text-[#9a9a9a]">Sistem Rapor Pintar</p>
            </div>

            <nav class="grid grid-cols-2 gap-2 px-3 py-4 sm:grid-cols-3 lg:flex lg:flex-1 lg:flex-col lg:gap-1 lg:px-4 lg:py-6">
                @foreach ($menus as $menu)
                    @php $isActive = $active === $menu['key']; @endphp
                    <a
                        href="{{ $menu['href'] }}"
                        class="{{ $isActive ? 'bg-black/8 text-[#171717] shadow-[inset_-4px_0_0_0_#171717]' : 'text-[#707070] hover:bg-black/5 hover:text-[#171717]' }} flex min-h-[50px] items-center gap-3 rounded-[8px] px-3 py-3 text-[14px] font-medium transition"
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
            </nav>
        </div>

        <div class="border-t border-black/5 bg-[#e5e5e3] px-4 py-4">
            <div class="mb-3 flex items-center gap-3">
                <div class="flex h-10 w-10 flex-none items-center justify-center rounded-[10px] bg-[#cfd4db] text-[12px] font-bold text-[#1a3a6b]">
                    {{ $initials }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-[13px] font-bold text-[#1d1d1d]">{{ $user->name }}</p>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-[#7a7a7a]">Wali Kelas</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="flex h-[38px] w-full items-center justify-center rounded-[4px] bg-black text-[12px] font-bold uppercase tracking-[0.12em] text-white transition hover:opacity-90" type="submit">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="min-w-0 flex-1">
        <header class="border-b border-black/5 bg-white px-5 py-4 sm:px-6 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-[24px] font-extrabold tracking-[-0.04em] text-[#1f1f1f]">{{ $title }}</h2>
                    @if ($subtitle)
                        <p class="mt-1 text-[13px] text-[#7c7c7c]">{{ $subtitle }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-between gap-4 sm:justify-end">
                    <button aria-label="Notifikasi" class="flex h-10 w-10 items-center justify-center rounded-full border border-black/8 text-[#3a3a3a] transition hover:bg-black/5" type="button">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.42V11a6 6 0 1 0-12 0v3.18a2 2 0 0 1-.59 1.41L4 17h5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            <path d="M9 17a3 3 0 0 0 6 0" stroke-linecap="round" stroke-width="2"></path>
                        </svg>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-[14px] font-bold text-[#2a2a2a]">{{ $user->name }}</p>
                            <p class="text-[11px] text-[#8a8a8a]">ID. {{ strtoupper($user->username ?? 'WALIKELAS') }}</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-[10px] bg-[#d8dde5] text-[12px] font-bold text-[#1a3a6b]">
                            {{ $initials }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-4 sm:px-6 sm:py-6 lg:px-10 lg:py-8">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
