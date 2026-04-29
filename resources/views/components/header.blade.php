{{-- ============================================================
     Komponen: Header (Top Bar)
     Deskripsi: Header bar yang tampil di atas konten utama.
     Menggantikan header yang sebelumnya inline di setiap layout.

     Output HTML IDENTIK dengan header di layouts lama.
     Berisi: hamburger toggle (mobile), judul halaman, info user.

     Fallback username otomatis dari role user yang login.
     ============================================================ --}}

@php
    // Ambil data user untuk ditampilkan di header
    $user = auth()->user();
    $initials = collect(explode(' ', trim($user->nama ?? 'SIRAPI')))
        ->filter()->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    // Fallback username berdasarkan role
    $fallbackUsername = strtoupper(getUserRole());
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
        <a href="{{ route('profil') }}" class="hidden items-center gap-2.5 sm:flex rounded-lg p-1.5 -m-1.5 transition hover:bg-[#f1f5f9]">
            <div class="text-right">
                <p class="text-[13px] font-bold text-[#1e293b]">{{ $user->nama }}</p>
                <p class="text-[10px] text-[#64748b]">{{ ($user->username ?? $fallbackUsername) }}</p>
            </div>
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white">
                {{ $initials }}
            </div>
        </a>
        <a href="{{ route('profil') }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white sm:hidden">
            {{ $initials }}
        </a>
    </div>
</header>