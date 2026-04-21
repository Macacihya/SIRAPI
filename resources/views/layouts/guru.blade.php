{{-- ============================================================
     Layout: guru.blade.php
     Deskripsi: Master layout untuk semua halaman Guru Mata Pelajaran.
     Di-extract dari guru-shell.blade.php (component lama)
     menjadi layout tradisional dengan @extends/@yield.

     Menggunakan komponen:
       - <x-guru-navbar> untuk sidebar navigasi

     Cara pakai:
       @extends('layouts.guru')
       @section('title', 'Dashboard')
       @section('subtitle', 'Selamat datang di Panel Guru')
       @section('active', 'dashboard')
       @section('content') ... @endsection
     ============================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Dashboard') - SIRAPI Guru</title>

    {{-- Tailwind CSS & Alpine.js --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs" defer></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar untuk sidebar */
        .sidebar-scrollbar::-webkit-scrollbar { width: 4px; }
        .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>

    {{-- Slot untuk CSS/JS tambahan per halaman --}}
    @yield('styles')
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

    {{-- ─── SIDEBAR (menggunakan Blade Component reusable) ───── --}}
    <x-guru-navbar :active="View::yieldContent('active', 'dashboard')" />

    {{-- ─── MAIN WRAPPER ──────────────────────────────────────── --}}
    <div class="flex min-h-screen flex-col lg:ml-[240px]">

        {{-- ─── HEADER ────────────────────────────────────────── --}}
        @php
            $user = auth()->user();
            $initials = collect(explode(' ', trim($user->name ?? 'SIRAPI')))
                ->filter()->take(2)
                ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                ->implode('');
        @endphp
        <header
            class="fixed top-0 right-0 left-0 z-30 flex h-[64px] items-center border-b border-[#e2e8f0] bg-white/95 px-4 backdrop-blur shadow-sm sm:px-6 lg:left-[240px] lg:px-8"
        >
            {{-- Kiri: Hamburger (mobile) + Judul halaman --}}
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
                    <h2 class="truncate text-[16px] font-extrabold tracking-tight text-[#0f172a] sm:text-[20px] lg:text-[24px]">@yield('title', 'Dashboard')</h2>
                    @hasSection('subtitle')
                        <p class="hidden text-[11px] text-[#64748b] sm:block">@yield('subtitle')</p>
                    @endif
                </div>
            </div>

            {{-- Kanan: Info user --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('guru.profil') }}" class="hidden items-center gap-2.5 sm:flex rounded-lg p-1.5 -m-1.5 transition hover:bg-[#f1f5f9]">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#1e293b]">{{ $user->name }}</p>
                        <p class="text-[10px] text-[#64748b]">ID. {{ strtoupper($user->username ?? 'GURU') }}</p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white">
                        {{ $initials }}
                    </div>
                </a>
                <a href="{{ route('guru.profil') }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white sm:hidden">
                    {{ $initials }}
                </a>
            </div>
        </header>

        {{-- ─── KONTEN UTAMA (diisi oleh child view) ──────────── --}}
        <main class="flex-1 px-4 pt-[80px] pb-6 sm:px-6 sm:pt-[88px] lg:px-10 lg:pt-[96px]">
            @yield('content')
        </main>
    </div>

    {{-- ─── MODAL KONFIRMASI LOGOUT ──────────────────────────── --}}
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

    {{-- ─── TOAST NOTIFICATION SYSTEM ────────────────────────── --}}
    <div
        x-data="{
            toasts: [],
            add(e) {
                const id = Date.now();
                this.toasts.push({ id, message: e.message, type: e.type || 'success', visible: true });
                setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 3200);
            }
        }"
        @toast.window="add($event.detail)"
        class="fixed top-20 right-4 z-[200] space-y-2 pointer-events-none"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="toast.visible"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 translate-x-8"
                class="pointer-events-auto flex items-center gap-3 rounded-xl border px-5 py-3.5 shadow-lg backdrop-blur"
                :class="{
                    'bg-white border-[#a7f3d0] text-[#059669]': toast.type === 'success',
                    'bg-white border-[#fecaca] text-[#dc2626]': toast.type === 'error',
                    'bg-white border-[#93c5fd] text-[#1d4ed8]': toast.type === 'info',
                }"
            >
                <template x-if="toast.type === 'success'">
                    <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path></svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 16v-4m0-4h.01" stroke-width="2" stroke-linecap="round"></path></svg>
                </template>
                <span class="text-[13px] font-bold" x-text="toast.message"></span>
            </div>
        </template>
    </div>

    {{-- Slot untuk JS tambahan per halaman --}}
    @yield('scripts')

</body>
</html>
