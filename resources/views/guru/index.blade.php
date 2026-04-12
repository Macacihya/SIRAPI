<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-56 bg-white border-r border-gray-200 flex flex-col min-h-screen fixed top-0 left-0">
        <div class="px-5 py-5 border-b border-gray-200">
            <p class="text-base font-semibold text-gray-800">SIRAPI</p>
            <p class="text-xs text-gray-400 mt-0.5">Management System</p>
        </div>

        <nav class="flex-1 px-2 py-4 space-y-0.5">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 px-3 mb-2">Menu</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
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

            {{-- Guru - active --}}
            <a href="{{ route('guru.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17v-2m3 2v-4m3 4v-6M5 21h14a2 2 0 002-2V7l-4-4H5a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Data Guru
            </a>

            <a href="{{ route('siswa.tampilkan') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Data Siswa
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

        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
            <h1 class="text-base font-semibold text-gray-800">Data Guru</h1>
            <span class="text-xs bg-gray-100 text-gray-500 border border-gray-200 px-3 py-1 rounded-md font-medium">
                2025/2026
            </span>
        </header>

        <main class="flex-1 p-8">

            @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
            @endif

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Total Guru</p>
                    <p class="text-3xl font-semibold text-gray-800">{{ $gurus->count() }}</p>
                    <p class="text-xs text-green-600 mt-1">↑ Terdaftar</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Mata Pelajaran</p>
                    <p class="text-3xl font-semibold text-gray-800">{{ $gurus->unique('mata_pelajaran')->count() }}</p>
                    <p class="text-xs text-blue-600 mt-1">Berbeda</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Tahun Ajaran</p>
                    <p class="text-3xl font-semibold text-gray-800">2025</p>
                    <p class="text-xs text-gray-400 mt-1">2025 / 2026</p>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800">Daftar Guru</h3>
                    <a href="{{ route('guru.create') }}"
                       class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition font-medium">
                        + Tambah Guru
                    </a>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">NIP</th>
                            <th class="px-6 py-3 text-left">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left">Sekolah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($gurus as $i => $g)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 text-gray-500">{{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-3 text-gray-800 font-medium">{{ $g->nama }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $g->nip }}</td>
                            <td class="px-6 py-3">
                                <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-md">
                                    {{ $g->mata_pelajaran }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ optional($g->sekolah)->nama_sekolah ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data guru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>