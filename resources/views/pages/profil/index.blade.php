{{-- ============================================================
     Halaman: Profil (dispatcher)
     Memuat partial yang sesuai berdasarkan role user.
     ============================================================ --}}

@extends('layouts.app')
@section('title', 'Profil Pengguna')
@section('subtitle', 'Informasi akun dan biodata')
@section('active', 'profil')

@section('content')
    @if(getUserRole() === 'admin')
        @include('pages.profil.partials.admin')
    @elseif(getUserRole() === 'guru')
        @include('pages.profil.partials.guru')
    @elseif(getUserRole() === 'walikelas')
        @include('pages.profil.partials.walikelas')
    @endif
@endsection
