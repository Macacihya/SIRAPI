{{-- Halaman: About — menggunakan layout guest --}}
@extends('layouts.guest')
@section('title', 'About')
@section('active', 'about')

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl p-8 mb-6">
        <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-md mb-4">Tentang Kami</span>
        <h1 class="text-3xl font-black text-gray-800 mb-4">Apa itu SIRAPI?</h1>
        <p class="text-sm text-gray-500 leading-relaxed mb-4">SIRAPI (Sistem Rapor Pintar) adalah platform manajemen rapor online yang dirancang khusus untuk membantu sekolah dalam mengelola data akademik siswa secara digital, efisien, dan transparan.</p>
        <p class="text-sm text-gray-500 leading-relaxed">Dengan SIRAPI, proses input nilai, rekap kehadiran, hingga pencetakan rapor dapat dilakukan dalam satu sistem terintegrasi yang dapat diakses oleh Admin, Guru, dan Wali Kelas sesuai hak aksesnya masing-masing.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Visi</p>
            <p class="text-sm text-gray-800 font-medium mb-1">Platform Rapor Terpercaya</p>
            <p class="text-xs text-gray-500">Menjadi platform rapor digital terpercaya untuk sekolah di Indonesia.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Misi</p>
            <p class="text-sm text-gray-800 font-medium mb-1">Sederhanakan Administrasi</p>
            <p class="text-xs text-gray-500">Menyederhanakan proses administrasi akademik dengan teknologi modern.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Nilai</p>
            <p class="text-sm text-gray-800 font-medium mb-1">Transparansi & Efisiensi</p>
            <p class="text-xs text-gray-500">Transparansi, efisiensi, dan kemudahan akses untuk semua pengguna.</p>
        </div>
    </div>
@endsection
