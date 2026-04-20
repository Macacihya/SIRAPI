{{-- ============================================================
     Halaman: Home
     Layout: layouts.guest (navbar sudah di dalam layout)
     Komponen: <x-public-navbar> (otomatis dari layout)

     Sebelum refactoring: navbar di-copy-paste manual.
     Sesudah: hanya konten halaman, navbar dari komponen.
     ============================================================ --}}

@extends('layouts.guest')
@section('title', 'Home')
@section('active', 'home')

@section('content')
    {{-- Hero Section --}}
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
@endsection
