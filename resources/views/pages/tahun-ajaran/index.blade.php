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

    <x-data-table :headers="['Tahun Ajaran', 'Semester', 'Status']">
            @forelse ($tahunAjarans as $tahunAjaran)
                <tr class="border-b dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }}
                    </td>
                    <td class="px-6 py-4">{{ $tahunAjaran->semester }}</td>
                    <td class="px-6 py-4">
                        @if ($tahunAjaran->is_active)
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Aktif</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">Tidak aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('tahun-ajaran.edit', $tahunAjaran->id) }}" class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('tahun-ajaran.destroy', $tahunAjaran->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data tahun ajaran.</td>
                </tr>
            @endforelse
    </x-data-table>
</div>
@endsection
