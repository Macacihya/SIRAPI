{{-- ============================================================
     Halaman: Dashboard (dispatcher)
     Memuat partial yang sesuai berdasarkan role user.
     Menggunakan getLayout() untuk pemilihan layout dinamis.
     ============================================================ --}}

@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di SIRAPI')
@section('active', 'dashboard')

@section('content')
    @if(getUserRole() === 'admin')
        @include('pages.dashboard.partials.admin')
    @elseif(getUserRole() === 'guru')
        @include('pages.dashboard.partials.guru')
    @elseif(getUserRole() === 'walikelas')
        @include('pages.dashboard.partials.walikelas')
    @endif
@endsection
