{{-- ============================================================
     Layout: guest.blade.php
     Deskripsi: Layout utama untuk halaman publik (Home, About, dll).
     Menggunakan komponen <x-public-navbar> agar navbar tidak
     perlu di-copy-paste di setiap halaman.

     Cara pakai:
       @extends('layouts.guest')
       @section('title', 'Home')
       @section('active', 'home')
       @section('content') ... @endsection
     ============================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIRAPI') - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Navbar publik — menggunakan Blade Component reusable --}}
    <x-public-navbar :active="View::yieldContent('active', 'home')" />

    {{-- Konten utama halaman (diisi oleh setiap halaman via @section) --}}
    <main class="flex-1 mt-16 p-8 max-w-5xl mx-auto w-full">
        @yield('content')
    </main>

</body>
</html>
