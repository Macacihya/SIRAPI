@extends('layouts.admin')

@section('title', 'Tambah Tahun Ajaran')
@section('active', 'tahun-ajaran')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Tambah Tahun Ajaran</h1>
        <a href="{{ route('tahun-ajaran.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="tahun_mulai" class="block mb-1">Tahun Mulai</label>
                <input id="tahun_mulai" type="number" name="tahun_mulai" value="{{ old('tahun_mulai') }}" class="w-full border p-2 rounded" min="2000">
            </div>

            <div>
                <label for="tahun_selesai" class="block mb-1">Tahun Selesai</label>
                <input id="tahun_selesai" type="number" name="tahun_selesai" value="{{ old('tahun_selesai') }}" class="w-full border p-2 rounded" min="2001">
            </div>
        </div>

        <div>
            <label for="semester" class="block mb-1">Semester</label>
            <select id="semester" name="semester" class="w-full border p-2 rounded">
                <option value="">Pilih semester</option>
                <option value="Ganjil" @selected(old('semester') === 'Ganjil')>Ganjil</option>
                <option value="Genap" @selected(old('semester') === 'Genap')>Genap</option>
            </select>
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active'))>
            <span>Jadikan periode aktif</span>
        </label>

        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Simpan
        </button>
    </form>
</div>
@endsection
