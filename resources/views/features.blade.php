<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between fixed top-0 left-0 right-0 z-10">
        <div>
            <p class="text-base font-semibold text-gray-800">SIRAPI</p>
            <p class="text-xs text-gray-400">Management System</p>
        </div>
        <div class="flex items-center gap-1 text-sm">
            <a href="/home" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Home</a>
            <a href="/about" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">About</a>
            <a href="/features" class="px-3 py-2 rounded-lg font-medium bg-gray-100 text-gray-900">Features</a>
            <a href="/services" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Services</a>
            <a href="/contact" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Contact</a>
            <a href="{{ route('login') }}" class="ml-2 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition font-medium">Login</a>
        </div>
    </nav>

    {{-- MAIN --}}
    <main class="flex-1 mt-16 p-8 max-w-5xl mx-auto w-full">

        <div class="bg-white border border-gray-200 rounded-xl p-8 mb-6">
            <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-md mb-4">Fitur Unggulan</span>
            <h1 class="text-3xl font-black text-gray-800 mb-2">Features SIRAPI</h1>
            <p class="text-sm text-gray-500">Fitur lengkap untuk mendukung pengelolaan rapor sekolah secara digital.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Manajemen Nilai</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Input Nilai Real-time</p>
                <p class="text-xs text-gray-500">Input dan kelola nilai siswa secara real-time untuk semua mata pelajaran.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Rapor Otomatis</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Generate Rapor</p>
                <p class="text-xs text-gray-500">Cetak rapor siswa secara otomatis setiap akhir semester dengan format standar.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Data Siswa & Guru</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Manajemen Pengguna</p>
                <p class="text-xs text-gray-500">Kelola data siswa, guru, dan wali kelas dalam satu sistem terintegrasi.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Kehadiran</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Rekap Kehadiran</p>
                <p class="text-xs text-gray-500">Rekap kehadiran siswa per semester yang terintegrasi langsung ke rapor.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Keamanan</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Akses Berbasis Role</p>
                <p class="text-xs text-gray-500">Hak akses berbeda untuk Admin, Guru, dan Wali Kelas demi keamanan data.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Aksesibilitas</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Berbasis Web</p>
                <p class="text-xs text-gray-500">Dapat diakses kapan saja dan di mana saja melalui browser tanpa instalasi.</p>
            </div>
        </div>

    </main>
</body>
</html>