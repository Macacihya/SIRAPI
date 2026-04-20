{{-- Halaman: Contact — menggunakan layout guest --}}
@extends('layouts.guest')
@section('title', 'Contact')
@section('active', 'contact')

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl p-8 mb-6">
        <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-md mb-4">Hubungi Kami</span>
        <h1 class="text-3xl font-black text-gray-800 mb-2">Kontak & Dukungan</h1>
        <p class="text-sm text-gray-500">Kami siap membantu Anda. Hubungi tim SIRAPI melalui kontak berikut.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Email</p>
            <p class="text-sm font-medium text-gray-800">admin@sirapi.com</p>
            <p class="text-xs text-gray-400 mt-1">Respon dalam 1x24 jam</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Telepon</p>
            <p class="text-sm font-medium text-gray-800">+62 812 3456 7890</p>
            <p class="text-xs text-gray-400 mt-1">Senin - Jumat, 08.00 - 17.00</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Alamat</p>
            <p class="text-sm font-medium text-gray-800">Jl. Pendidikan No. 1</p>
            <p class="text-xs text-gray-400 mt-1">Jakarta, Indonesia</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Dukungan Teknis</p>
            <p class="text-sm font-medium text-gray-800">support@sirapi.com</p>
            <p class="text-xs text-gray-400 mt-1">Untuk kendala teknis sistem</p>
        </div>
    </div>
@endsection
