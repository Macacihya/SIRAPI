{{-- ============================================================
     Layout: walikelas.blade.php
     Deskripsi: Master layout untuk semua halaman Wali Kelas.
     Di-extract dari walikelas-shell.blade.php.

     Cara pakai:
       @extends('layouts.walikelas')
       @section('title', 'Profil Kelas')
       @section('subtitle', 'Selamat datang di Panel Wali Kelas')
       @section('active', 'profil-kelas')
       @section('content') ... @endsection
     ============================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Dashboard') - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-scrollbar::-webkit-scrollbar { width: 4px; }
        .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen bg-[#f0f4f8] text-[#1e293b]" x-data="{ sidebarOpen: false, logoutModalOpen: false }">

    {{-- Backdrop mobile --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" style="display: none;"
    ></div>

    {{-- Sidebar (menggunakan Blade Component reusable) --}}
    <x-walikelas-navbar :active="View::yieldContent('active', 'dashboard')" />

    {{-- Main Wrapper --}}
    <div class="flex min-h-screen flex-col lg:ml-[240px]">

        {{-- Header --}}
        @php
            $user = auth()->user();
            $initials = collect(explode(' ', trim($user->name ?? 'SIRAPI')))
                ->filter()->take(2)
                ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                ->implode('');
        @endphp
        <header class="fixed top-0 right-0 left-0 z-30 flex h-[64px] items-center border-b border-[#e2e8f0] bg-white/95 px-4 backdrop-blur shadow-sm sm:px-6 lg:left-[240px] lg:px-8">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <button @click="sidebarOpen = !sidebarOpen" class="flex h-9 w-9 flex-none items-center justify-center rounded-lg border border-[#cbd5e1] text-[#475569] transition hover:bg-[#f1f5f9] hover:text-[#0f172a] lg:hidden" aria-label="Toggle Sidebar">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarOpen" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        <path x-show="sidebarOpen" d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="display: none;"></path>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h2 class="truncate text-[16px] font-extrabold tracking-tight text-[#0f172a] sm:text-[20px] lg:text-[24px]">@yield('title', 'Dashboard')</h2>
                    @hasSection('subtitle')
                        <p class="hidden text-[11px] text-[#64748b] sm:block">@yield('subtitle')</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <button aria-label="Notifikasi" class="flex h-9 w-9 items-center justify-center rounded-full border border-[#cbd5e1] text-[#475569] transition hover:bg-[#f1f5f9]" type="button">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.42V11a6 6 0 1 0-12 0v3.18a2 2 0 0 1-.59 1.41L4 17h5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M9 17a3 3 0 0 0 6 0" stroke-linecap="round" stroke-width="2"></path></svg>
                </button>
                <a href="{{ route('profil') }}" class="hidden items-center gap-2.5 sm:flex rounded-lg p-1.5 -m-1.5 transition hover:bg-[#f1f5f9]">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#1e293b]">{{ $user->name }}</p>
                        <p class="text-[10px] text-[#64748b]">ID. {{ strtoupper($user->username ?? 'WALIKELAS') }}</p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white">{{ $initials }}</div>
                </a>
                <a href="{{ route('profil') }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white sm:hidden">{{ $initials }}</a>
            </div>
        </header>

        {{-- Konten utama --}}
        <main class="flex-1 px-4 pt-[80px] pb-6 sm:px-6 sm:pt-[88px] lg:px-10 lg:pt-[96px]">
            @yield('content')
        </main>
    </div>

    {{-- Modal Logout --}}
    <div x-show="logoutModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="logoutModalOpen = false">
        <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] mb-4 ring-4 ring-[#fee2e2]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                <h3 class="text-[18px] font-black text-[#0f172a]">Konfirmasi Keluar</h3>
                <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Apakah Anda yakin ingin keluar dari sesi panel Wali Kelas ini?</p>
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
