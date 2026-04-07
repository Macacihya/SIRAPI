<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-56 bg-white border-r border-gray-200 flex flex-col min-h-screen fixed top-0 left-0">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-gray-200">
            <p class="text-base font-semibold text-gray-800">SIRAPI</p>
            <p class="text-xs text-gray-400 mt-0.5">Management System</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 space-y-0.5">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 px-3 mb-2">Menu</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('sekolah.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Data Sekolah
            </a>

            <a href="{{ route('guru.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17v-2m3 2v-4m3 4v-6M5 21h14a2 2 0 002-2V7l-4-4H5a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Data Guru
            </a>

            <a href="#"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" stroke-width="2"/>
                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" stroke-width="2"/>
                </svg>
                Pengaturan
            </a>
        </nav>

        {{-- User + Logout --}}
        <div class="px-4 py-4 border-t border-gray-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-sm text-red-500 hover:text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg text-left transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="ml-56 flex-1 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
            <h1 class="text-base font-semibold text-gray-800">Dashboard</h1>
            <span class="text-xs bg-gray-100 text-gray-500 border border-gray-200 px-3 py-1 rounded-md font-medium">
                2025/2026
            </span>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-8">

            {{-- Welcome --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">
                    Selamat datang, {{ Auth::user()->name }}!
                </h2>
                <p class="text-sm text-gray-500">
                    Anda login sebagai <span class="font-medium text-gray-700">{{ Auth::user()->role }}</span> &mdash; Sistem Rapor Pintar
                </p>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Total Data</p>
                    <p class="text-3xl font-semibold text-gray-800">128</p>
                    <p class="text-xs text-green-600 mt-1">↑ Data aktif</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Pending</p>
                    <p class="text-3xl font-semibold text-gray-800">14</p>
                    <p class="text-xs text-yellow-600 mt-1">Perlu ditinjau</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Selesai</p>
                    <p class="text-3xl font-semibold text-gray-800">97</p>
                    <p class="text-xs text-green-600 mt-1">Bulan ini</p>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">Data Terbaru</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Keterangan</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 text-gray-500">001</td>
                            <td class="px-6 py-3 text-gray-800 font-medium">Item Pertama</td>
                            <td class="px-6 py-3 text-gray-500">Keterangan contoh</td>
                            <td class="px-6 py-3">
                                <span class="bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded-md">Aktif</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 text-gray-500">002</td>
                            <td class="px-6 py-3 text-gray-800 font-medium">Item Kedua</td>
                            <td class="px-6 py-3 text-gray-500">Keterangan contoh</td>
                            <td class="px-6 py-3">
                                <span class="bg-yellow-50 text-yellow-700 text-xs font-medium px-2.5 py-1 rounded-md">Pending</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 text-gray-500">003</td>
                            <td class="px-6 py-3 text-gray-800 font-medium">Item Ketiga</td>
                            <td class="px-6 py-3 text-gray-500">Keterangan contoh</td>
                            <td class="px-6 py-3">
                                <span class="bg-red-50 text-red-600 text-xs font-medium px-2.5 py-1 rounded-md">Nonaktif</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>
