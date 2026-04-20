{{-- Halaman: Services — menggunakan layout guest --}}
@extends('layouts.guest')
@section('title', 'Services')
@section('active', 'services')

@section('content')
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
@endsection
