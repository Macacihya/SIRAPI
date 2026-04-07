<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Guru</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Guru</h1>
        <a href="{{ route('guru.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
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
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">NIP</th>
                <th class="p-3 text-left">Mata Pelajaran</th>
                <th class="p-3 text-left">Sekolah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($gurus as $g)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $g->nama }}</td>
                    <td class="p-3">{{ $g->nip }}</td>
                    <td class="p-3">{{ $g->mata_pelajaran }}</td>
                    <td class="p-3 text-blue-600">{{ optional($g->sekolah)->nama_sekolah ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">Belum ada data guru.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
