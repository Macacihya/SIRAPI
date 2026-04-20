{{-- ============================================================
     Komponen: PublicNavbar
     Deskripsi: Navbar untuk halaman publik (Home, About, dll).
     Prop: $active — menu yang sedang aktif
     Dibuat dengan: php artisan make:component PublicNavbar
     ============================================================ --}}

@php
    // Daftar menu navigasi publik
    $navItems = [
        ['key' => 'home',     'label' => 'Home',     'href' => '/home'],
        ['key' => 'about',    'label' => 'About',    'href' => '/about'],
        ['key' => 'features', 'label' => 'Features', 'href' => '/features'],
        ['key' => 'services', 'label' => 'Services', 'href' => '/services'],
        ['key' => 'contact',  'label' => 'Contact',  'href' => '/contact'],
    ];
@endphp

{{-- Navbar fixed di atas halaman --}}
<nav class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between fixed top-0 left-0 right-0 z-10">
    {{-- Brand / Logo --}}
    <div>
        <p class="text-base font-semibold text-gray-800">SIRAPI</p>
        <p class="text-xs text-gray-400">Management System</p>
    </div>

    {{-- Menu Links + Tombol Login --}}
    <div class="flex items-center gap-1 text-sm">
        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"
               class="px-3 py-2 rounded-lg {{ $active === $item['key']
                   ? 'font-medium bg-gray-100 text-gray-900'
                   : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors' }}">
                {{ $item['label'] }}
            </a>
        @endforeach

        {{-- Tombol Login --}}
        <a href="{{ route('login') }}"
           class="ml-2 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition font-medium">
            Login
        </a>
    </div>
</nav>