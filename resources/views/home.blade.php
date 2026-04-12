<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SIRAPI</title>
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
            <a href="/home" class="px-3 py-2 rounded-lg font-medium bg-gray-100 text-gray-900">Home</a>
            <a href="/about" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">About</a>
            <a href="/features" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Features</a>
            <a href="/services" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Services</a>
            <a href="/contact" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Contact</a>
            <a href="{{ route('login') }}" class="ml-2 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition font-medium">Login</a>
        </div>
    </nav>

    {{-- MAIN --}}
    <main class="flex-1 mt-16 p-8 max-w-5xl mx-auto w-full">

        {{-- Hero --}}
        <div class="bg-white border border-gray-200 rounded-xl p-10 mb-6 text-center">
            <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-md mb-4">Sistem Rapor Pintar</span>
            <h1 class="text-4xl font-black text-gray-800 leading-tight mb-4">Kelola Rapor Siswa<br>Lebih Mudah & Efisien</h1>
            <p class="text-gray-500 max-w-xl mx-auto mb-8 text-sm leading-relaxed">SIRAPI hadir sebagai solusi digital untuk pengelolaan nilai, kehadiran, dan pelaporan hasil belajar siswa secara transparan dan terintegrasi.</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('login') }}" class="bg-gray-800 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-700 transition text-sm">Mulai Sekarang</a>
                <a href="/features" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-50 transition text-sm">Lihat Fitur</a>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Sekolah Terdaftar</p>
                <p class="text-3xl font-semibold text-gray-800">100+</p>
                <p class="text-xs text-green-600 mt-1">↑ Aktif</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Siswa Aktif</p>
                <p class="text-3xl font-semibold text-gray-800">10K+</p>
                <p class="text-xs text-green-600 mt-1">↑ Terdaftar</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Guru Terdaftar</p>
                <p class="text-3xl font-semibold text-gray-800">500+</p>
                <p class="text-xs text-green-600 mt-1">↑ Aktif</p>
            </div>
        </div>

    </main>
</body>
</html>