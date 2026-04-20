{{-- ============================================================
     Layout: auth.blade.php
     Deskripsi: Layout minimal untuk halaman autentikasi
     (Login, Forgot Password, Reset Password).
     Hanya menyediakan <head> dan wrapper, karena halaman auth
     memiliki desain unik (split screen).

     Cara pakai:
       @extends('layouts.auth')
       @section('title', 'Login')
       @section('content') ... @endsection
     ============================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - SIRAPI</title>

    {{-- Google Fonts: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    {{-- Material Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    {{-- CSS Variables untuk konsistensi warna --}}
    <style>
        :root {
            --page-bg: #f4f7fb;
            --panel-bg: #eff4fb;
            --text-main: #0f172a;
            --text-soft: #475569;
            --text-muted: #64748b;
            --field-bg: #eef2f7;
            --field-border: #d9e2ec;
            --line-soft: rgba(15, 23, 42, 0.06);
            --brand-1: #1a3a6b;
            --brand-2: #1e4d9b;
            --brand-3: #0f2347;
        }

        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .brand-diamond::before {
            content: "";
            position: absolute;
            inset: 0;
            border: 1px solid var(--line-soft);
            transform: rotate(45deg);
            border-radius: 6px;
        }
        .ghost-block {
            position: absolute;
            border-radius: 18px;
            background: rgba(26, 58, 107, 0.055);
        }
        select { background-image: none !important; }
    </style>

    {{-- Section untuk style tambahan dari child view --}}
    @yield('styles')
</head>
<body class="min-h-screen bg-[var(--page-bg)] text-[var(--text-main)]">

    {{-- Konten halaman auth (diisi oleh child view) --}}
    @yield('content')

    {{-- Section untuk script tambahan dari child view --}}
    @yield('scripts')
</body>
</html>
