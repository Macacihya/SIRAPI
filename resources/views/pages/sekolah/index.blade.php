<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Sekolah</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Sekolah</h1>
        <a href="{{ route('sekolah.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
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
                <th class="p-3 text-left">Nama Sekolah</th>
                <th class="p-3 text-left">Alamat</th>
                <th class="p-3 text-left">Telepon</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sekolahs as $s)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3">{{ $s->nama_sekolah }}</td>
                    <td class="p-3">{{ $s->alamat }}</td>
                    <td class="p-3">{{ $s->telepon }}</td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="{{ route('sekolah.edit', $s->id) }}" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                                Edit
                            </a>
                            <form action="{{ route('sekolah.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data sekolah ini?')">
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
                    <td colspan="4" class="p-4 text-center text-gray-500">Belum ada data sekolah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
