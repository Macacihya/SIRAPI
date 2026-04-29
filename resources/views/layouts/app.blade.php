{{-- ============================================================
     Layout: app.blade.php (Master Layout Terpusat)
     Deskripsi: SATU layout untuk SEMUA role (admin, guru, walikelas).
     Menggantikan: layouts/admin, layouts/guru, layouts/walikelas.

     Arsitektur komponen:
       - <x-sidebar>       → Navigasi sidebar (dari config)
       - <x-header>        → Top bar (judul + user info)
       - <x-modal-logout>  → Modal konfirmasi keluar
       - <x-toast>         → Notifikasi global

     Cara pakai:
       @extends('layouts.app')
       @section('title', 'Dashboard')
       @section('subtitle', 'Selamat datang di SIRAPI')
       @section('active', 'dashboard')
       @section('content') ... @endsection
     ============================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Dashboard') - SIRAPI</title>

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

    {{-- ─── SIDEBAR (komponen terpisah, menu dari config) ──────── --}}
    <x-sidebar :active="View::yieldContent('active', 'dashboard')" />

    {{-- ─── MAIN WRAPPER ──────────────────────────────────────── --}}
    <div class="flex min-h-screen flex-col lg:ml-[240px]">

        {{-- ─── HEADER (komponen terpisah) ─────────────────────── --}}
        @include('components.header')

        {{-- ─── KONTEN UTAMA (diisi oleh child view) ──────────── --}}
        <main class="flex-1 px-4 pt-[80px] pb-6 sm:px-6 sm:pt-[88px] lg:px-10 lg:pt-[96px]">
            @yield('content')
        </main>
    </div>

    {{-- ─── MODAL KONFIRMASI LOGOUT (komponen terpisah) ────────── --}}
    <x-modal-logout />

    {{-- ─── TOAST NOTIFICATION SYSTEM (komponen terpisah) ──────── --}}
    <x-toast />

    {{-- Stack untuk modal dari child views --}}
    @stack('modals')

</body>
</html>
