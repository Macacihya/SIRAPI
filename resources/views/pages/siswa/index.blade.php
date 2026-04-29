{{-- ============================================================
     Halaman: Data Siswa (dispatcher)
     Memuat partial yang sesuai berdasarkan role user.
     - Admin: CRUD lengkap
     - Guru: Read-only dengan filter & export
     ============================================================ --}}

@extends('layouts.app')
@section('title', 'Data Siswa')
@section('subtitle', 'Data peserta didik')
@section('active', 'data-siswa')

@section('content')
    @if(getUserRole() === 'admin')
        @include('pages.siswa.partials.admin')
    @elseif(in_array(getUserRole(), ['guru', 'walikelas', 'wali_kelas']))
        @include('pages.siswa.partials.guru')
    @endif
@endsection
