<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --page-bg:    #f4f7fb;
            --panel-bg:   #eff4fb;
            --text-main:  #0f172a;
            --text-soft:  #475569;
            --text-muted: #64748b;
            --field-bg:   #eef2f7;
            --field-border:#d9e2ec;
            --brand-1:    #1a3a6b;
            --brand-2:    #1e4d9b;
            --brand-3:    #0f2347;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body { background: var(--page-bg); color: var(--text-main); }

        /* Sidebar */
        aside { background: #fff; border-right: 1px solid var(--field-border); }
        .sidebar-brand-icon { background: var(--brand-3); }
        .sidebar-badge { background: var(--panel-bg); border: 1px solid var(--field-border); }
        .sidebar-badge-label { color: var(--text-muted); }
        .sidebar-badge-year  { color: var(--brand-1); }

        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 12px; border-radius:8px; font-size:14px; color: var(--text-soft); transition:all .15s; text-decoration:none; }
        .sidebar-link:hover  { background: var(--panel-bg); color: var(--brand-3); }
        .sidebar-link.active { background: var(--panel-bg); color: var(--brand-3); font-weight:700; }
        .sidebar-link svg    { width:16px; height:16px; flex-shrink:0; }
        .sidebar-footer-border { border-top: 1px solid var(--field-border); }

        /* Top bar */
        header { background:#fff; border-bottom: 1px solid var(--field-border); }
        .topbar-title { color: var(--brand-3); }
        .topbar-btn { border-radius:8px; color: var(--text-muted); transition:background .15s; }
        .topbar-btn:hover { background: var(--panel-bg); color: var(--brand-1); }
        .topbar-avatar { background: var(--panel-bg); border: 1px solid var(--field-border); color: var(--brand-1); font-weight:700; }

        /* Stat cards */
        .stat-card { background:#fff; border:1px solid var(--field-border); border-radius:12px; }
        .stat-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color: var(--text-muted); }
        .stat-value { font-size:30px; font-weight:800; color: var(--brand-3); }
        .stat-sub-green { color:#059669; font-size:12px; }
        .stat-sub-blue  { color: var(--brand-2); font-size:12px; }
        .stat-sub-muted { color: var(--text-muted); font-size:12px; }

        /* Table card */
        .table-card { background:#fff; border:1px solid var(--field-border); border-radius:12px; }
        .toolbar-border { border-bottom:1px solid var(--field-border); }
        thead th { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color: var(--text-muted); background: var(--panel-bg); }
        tbody tr:hover { background: var(--panel-bg); }
        .row-divider { border-bottom:1px solid var(--field-border); }

        /* Mapel badge */
        .mapel-badge { background: var(--panel-bg); color: var(--brand-2); border:1px solid var(--field-border); font-size:12px; font-weight:700; padding:2px 10px; border-radius:6px; }

        /* Status badges */
        .status-aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .status-other { background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; }

        /* Select / toolbar */
        .toolbar-select { appearance:none; padding:6px 32px 6px 36px; border:1px solid var(--field-border); border-radius:8px; font-size:14px; color: var(--text-main); background: var(--field-bg); cursor:pointer; }
        .toolbar-select:focus { outline:none; border-color: var(--brand-2); box-shadow:0 0 0 3px rgba(30,77,155,.12); }

        /* Buttons */
        .btn-outline { display:flex; align-items:center; gap:6px; padding:10px 16px; border:1px solid var(--field-border); border-radius:8px; font-size:14px; font-weight:600; color: var(--brand-1); background:#fff; transition:background .15s; text-decoration:none; }
        .btn-outline:hover { background: var(--panel-bg); }
        .btn-primary { display:flex; align-items:center; gap:6px; padding:10px 16px; border-radius:8px; font-size:14px; font-weight:600; color:#fff; background: linear-gradient(135deg, var(--brand-1), var(--brand-2)); transition:opacity .15s; text-decoration:none; }
        .btn-primary:hover { opacity:.9; }

        /* Action btn */
        .action-btn { padding:6px; border-radius:8px; color: var(--text-muted); transition:background .15s,color .15s; }
        .action-btn:hover { background: var(--panel-bg); color: var(--brand-1); }

        /* Dropdown */
        .dropdown-menu { position:absolute; margin-top:4px; width:144px; background:#fff; border:1px solid var(--field-border); border-radius:10px; box-shadow:0 8px 20px rgba(15,23,42,.1); z-index:10; overflow:hidden; }
        .dropdown-item { display:flex; align-items:center; gap:8px; padding:8px 12px; font-size:14px; color: var(--text-soft); transition:background .12s; text-decoration:none; width:100%; text-align:left; background:none; border:none; cursor:pointer; }
        .dropdown-item:hover { background: var(--panel-bg); color: var(--brand-3); }
        .dropdown-item-danger { color:#ef4444; }
        .dropdown-item-danger:hover { background:#fef2f2; color:#dc2626; }

        /* Pagination */
        .pagination-border { border-top:1px solid var(--field-border); }
        .page-btn { width:32px; height:32px; border-radius:8px; font-size:14px; font-weight:600; display:flex; align-items:center; justify-content:center; cursor:pointer; border:none; background:none; color: var(--text-muted); transition:background .12s; }
        .page-btn:hover { background: var(--panel-bg); color: var(--brand-1); }
        .page-btn-active { background: var(--brand-1); color:#fff; }
        .page-arrow { padding:6px; border-radius:8px; border:none; background:none; color: var(--text-muted); cursor:pointer; }
        .page-arrow:hover { background: var(--panel-bg); color: var(--brand-1); }

        /* Bottom info cards */
        .info-card { background:#fff; border:1px solid var(--field-border); border-radius:12px; }
        .info-icon-box { width:36px; height:36px; border-radius:8px; background: var(--panel-bg); display:flex; align-items:center; justify-content:center; margin-bottom:12px; }
        .info-icon-box svg { color: var(--brand-1); }

        /* Flash message */
        .flash-success { background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; }

        /* Fade */
        .fade-in { animation: fadeIn .25s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- ─── SIDEBAR ─────────────────────────────────────────────── --}}
    <aside class="w-56 flex flex-col min-h-screen fixed top-0 left-0 z-20">

        {{-- Brand --}}
        <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid var(--field-border)">
            <div class="sidebar-brand-icon w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M12 14l6.16-3.422A12 12 0 0112 21.5a12 12 0 01-6.16-10.922L12 14z" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-black leading-tight" style="color:var(--brand-3)">SIRAPI</p>
                <p class="text-[10px]" style="color:var(--text-muted)">Admin TU</p>
            </div>
        </div>

        {{-- Academic badge --}}
        <div class="sidebar-badge mx-3 mt-3 px-3 py-2 rounded-lg">
            <p class="sidebar-badge-label text-[10px] font-semibold uppercase tracking-wider">TU Administration</p>
            <p class="sidebar-badge-year text-xs mt-0.5 font-bold">ACADEMIC YEAR 2025/2026</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 space-y-0.5">
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
                </svg>
                Dashboard
            </a>
            <a href="#" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0zm6-4a2 2 0 11-4 0 2 2 0 014 0zM5 8a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Manajemen User
            </a>
            <a href="{{ route('sekolah.index') }}" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Data Sekolah
            </a>
            <a href="{{ route('guru.index') }}" class="sidebar-link active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guru &amp; Tendik
            </a>
            <a href="{{ route('siswa.tampilkan') }}" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0zm6-4a2 2 0 11-4 0 2 2 0 014 0zM5 8a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Data Siswa
            </a>
            <a href="#" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Akademik
            </a>
            <a href="#" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Jadwal Pelajaran
            </a>
            <a href="#" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Aturan Nilai
            </a>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer-border px-3 py-4 space-y-1">
            <a href="#" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <path d="M12 8v4m0 4h.01" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Bantuan
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left" style="color:#ef4444">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── MAIN ────────────────────────────────────────────────── --}}
    <div class="ml-56 flex-1 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <header class="px-8 py-3.5 flex items-center justify-between sticky top-0 z-10">
            <p class="topbar-title text-sm font-black tracking-tight">SIRAPI Admin TU</p>
            <div class="flex items-center gap-3">
                <button class="topbar-btn relative p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                </button>
                <button class="topbar-btn p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" stroke-width="2"/>
                    </svg>
                </button>
                <div class="topbar-avatar w-8 h-8 rounded-full flex items-center justify-center text-xs cursor-pointer">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="flex-1 p-8 fade-in">

            {{-- Flash --}}
            @if (session('success'))
            <div class="flash-success mb-6 flex items-center gap-3 rounded-xl px-4 py-3 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#059669">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Page title --}}
            <div class="flex items-start justify-between mb-7">
                <div>
                    <h1 class="text-2xl font-black tracking-tight" style="color:var(--brand-3)">Data Guru</h1>
                    <p class="text-sm mt-1" style="color:var(--text-soft)">Pengelolaan data induk guru, mata pelajaran, dan penugasan mengajar.</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="#" class="btn-outline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Export CSV
                    </a>
                    <a href="{{ route('guru.create') }}" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Tambah Guru
                    </a>
                </div>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="stat-card p-5">
                    <p class="stat-label mb-3">Total Guru</p>
                    <p class="stat-value">{{ $gurus->count() }}</p>
                    <p class="stat-sub-green mt-1.5">↑ Terdaftar</p>
                </div>
                <div class="stat-card p-5">
                    <p class="stat-label mb-3">Mata Pelajaran</p>
                    <p class="stat-value">{{ $gurus->unique('mata_pelajaran')->count() }}</p>
                    <p class="stat-sub-blue mt-1.5">Berbeda</p>
                </div>
                <div class="stat-card p-5">
                    <p class="stat-label mb-3">Guru Aktif</p>
                    <p class="stat-value">{{ $gurus->where('status', 'Aktif')->count() ?: $gurus->count() }}</p>
                    <p class="stat-sub-green mt-1.5">Mengajar</p>
                </div>
                <div class="stat-card p-5">
                    <p class="stat-label mb-3">Tahun Ajaran</p>
                    <p class="stat-value">2025</p>
                    <p class="stat-sub-muted mt-1.5">2025 / 2026</p>
                </div>
            </div>

            {{-- Table card --}}
            <div class="table-card overflow-hidden mb-6">

                {{-- Toolbar --}}
                <div class="toolbar-border px-6 py-4 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <select class="toolbar-select">
                                <option>Semua Mapel</option>
                                @foreach($gurus->unique('mata_pelajaran') as $g)
                                    <option>{{ $g->mata_pelajaran }}</option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path d="M3 4h18M7 8h10M11 12h4" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="relative">
                            <select class="toolbar-select">
                                <option>Semua Sekolah</option>
                                @foreach($gurus->unique(fn($g) => optional($g->sekolah)->nama_sekolah) as $g)
                                    @if(optional($g->sekolah)->nama_sekolah)
                                        <option>{{ $g->sekolah->nama_sekolah }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs" style="color:var(--text-muted)">Menampilkan 1–{{ $gurus->count() }} dari {{ $gurus->count() }} guru</p>
                </div>

                {{-- Table --}}
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 focus:ring-0 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 text-left">Guru</th>
                            <th class="px-4 py-3 text-left">NIP</th>
                            <th class="px-4 py-3 text-left">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-left">Sekolah</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $g)
                        <tr class="row-divider transition-colors">
                            <td class="px-6 py-3.5">
                                <input type="checkbox" class="row-cb rounded border-gray-300 focus:ring-0 cursor-pointer">
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background:var(--panel-bg);border:1px solid var(--field-border);color:var(--brand-1)">
                                        {{ strtoupper(substr($g->nama, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold" style="color:var(--brand-3)">{{ $g->nama }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-xs font-mono" style="color:var(--text-muted)">{{ $g->nip }}</td>
                            <td class="px-4 py-3.5">
                                <span class="mapel-badge">{{ $g->mata_pelajaran }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-sm" style="color:var(--text-soft)">
                                {{ optional($g->sekolah)->nama_sekolah ?? '—' }}
                            </td>
                            <td class="px-4 py-3.5">
                                @php $status = $g->status ?? 'Aktif'; @endphp
                                @if($status === 'Aktif')
                                    <span class="status-aktif inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                        AKTIF
                                    </span>
                                @else
                                    <span class="status-other inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400 inline-block"></span>
                                        {{ strtoupper($status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 relative">
                                <button onclick="toggleMenu(this)" class="action-btn">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu hidden">
                                    <a href="#" class="dropdown-item">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"/></svg>
                                        Detail
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                                        Edit
                                    </a>
                                    <button class="dropdown-item dropdown-item-danger">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center" style="color:var(--text-muted)">
                                <svg class="w-10 h-10 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--field-border)">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Belum ada data guru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="pagination-border px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm" style="color:var(--text-soft)">
                        <span>Baris per halaman:</span>
                        <select class="toolbar-select" style="padding:4px 28px 4px 8px">
                            <option>10</option><option>25</option><option>50</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-1">
                        <button class="page-arrow"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                        <button class="page-btn page-btn-active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-arrow"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                    </div>
                </div>
            </div>

            {{-- Bottom info cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="info-card p-6">
                    <h3 class="text-sm font-bold mb-2" style="color:var(--brand-3)">Verifikasi Data Guru</h3>
                    <p class="text-xs leading-relaxed" style="color:var(--text-soft)">
                        Pastikan semua NIP sudah sesuai dengan data Dapodik. Guru yang belum terverifikasi akan ditandai dengan ikon peringatan pada kolom aksi.
                    </p>
                </div>
                <div class="info-card p-6">
                    <div class="info-icon-box">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold mb-1" style="color:var(--brand-3)">Distribusi Mapel</h3>
                    <p class="text-xs leading-relaxed" style="color:var(--text-soft)">
                        Pantau pemerataan beban mengajar antar guru berdasarkan mata pelajaran dan kelas yang diampu.
                    </p>
                </div>
                <div class="info-card p-6">
                    <div class="info-icon-box">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold mb-1" style="color:var(--brand-3)">Riwayat Perubahan</h3>
                    <p class="text-xs leading-relaxed" style="color:var(--text-soft)">
                        Setiap perubahan data guru dicatat dalam log sistem untuk keperluan audit administrasi.
                    </p>
                </div>
            </div>

        </main>
    </div>

    <script>
        function toggleMenu(btn) {
            const menu = btn.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(m => { if (m !== menu) m.classList.add('hidden'); });
            menu.classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[onclick]')) document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        });
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('.row-cb').forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>