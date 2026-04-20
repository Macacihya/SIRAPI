{{-- ============================================================
     Halaman: Dashboard (utama setelah login)
     Layout: layouts.dashboard (sidebar + header dari layout)

     Sebelum: sidebar + header di-tulis inline (90 baris).
     Sesudah: hanya konten halaman, sidebar dari layout.
     ============================================================ --}}

@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('active', 'dashboard')

@section('content')
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
                    <td class="px-6 py-3"><span class="bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded-md">Aktif</span></td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 text-gray-500">002</td>
                    <td class="px-6 py-3 text-gray-800 font-medium">Item Kedua</td>
                    <td class="px-6 py-3 text-gray-500">Keterangan contoh</td>
                    <td class="px-6 py-3"><span class="bg-yellow-50 text-yellow-700 text-xs font-medium px-2.5 py-1 rounded-md">Pending</span></td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 text-gray-500">003</td>
                    <td class="px-6 py-3 text-gray-800 font-medium">Item Ketiga</td>
                    <td class="px-6 py-3 text-gray-500">Keterangan contoh</td>
                    <td class="px-6 py-3"><span class="bg-red-50 text-red-600 text-xs font-medium px-2.5 py-1 rounded-md">Nonaktif</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
