<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - SIRAPI</title>
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
            <a href="/features" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Features</a>
            <a href="/services" class="px-3 py-2 rounded-lg font-medium bg-gray-100 text-gray-900">Services</a>
            <a href="/contact" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Contact</a>
            <a href="{{ route('login') }}" class="ml-2 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition font-medium">Login</a>
        </div>
    </nav>

    {{-- MAIN --}}
    <main class="flex-1 mt-16 p-8 max-w-5xl mx-auto w-full">

        <div class="bg-white border border-gray-200 rounded-xl p-8 mb-6">
            <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-md mb-4">Layanan Kami</span>
            <h1 class="text-3xl font-black text-gray-800 mb-2">Services SIRAPI</h1>
            <p class="text-sm text-gray-500">Layanan yang tersedia untuk mendukung pengelolaan rapor sekolah Anda.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Rapor Digital</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Pengelolaan Rapor Digital</p>
                <p class="text-xs text-gray-500">Pembuatan dan pengelolaan rapor siswa secara digital. Rapor dapat dicetak atau diunduh kapan saja.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Implementasi</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Konsultasi & Implementasi</p>
                <p class="text-xs text-gray-500">Tim kami siap membantu proses implementasi SIRAPI dari setup awal hingga pelatihan penggunaan sistem.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Dukungan</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Dukungan Teknis 24/7</p>
                <p class="text-xs text-gray-500">Layanan dukungan teknis tersedia setiap saat untuk memastikan sistem berjalan lancar.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Analitik</p>
                <p class="text-sm font-medium text-gray-800 mb-1">Laporan & Analitik</p>
                <p class="text-xs text-gray-500">Laporan lengkap perkembangan akademik siswa, rekap nilai per kelas, dan statistik kehadiran.</p>
            </div>
        </div>

    </main>
</body>
</html>