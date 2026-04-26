@extends('layouts.app')

@section('title', 'Data Tahun Ajaran')
@section('active', 'tahun-ajaran')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Data Tahun Ajaran</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola periode tahun ajaran dan semester aktif.</p>
        </div>
        <a href="{{ route('tahun-ajaran.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            + Tambah
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Tahun Ajaran</th>
                <th class="p-3 text-left">Semester</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tahunAjarans as $tahunAjaran)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }}</td>
                    <td class="p-3">{{ $tahunAjaran->semester }}</td>
                    <td class="p-3">
                        @if ($tahunAjaran->is_active)
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Aktif</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600">Tidak aktif</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="{{ route('tahun-ajaran.edit', $tahunAjaran->id) }}" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                                Edit
                            </a>
                            <form action="{{ route('tahun-ajaran.destroy', $tahunAjaran->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">Belum ada data tahun ajaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
