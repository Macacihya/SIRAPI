<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Sekolah</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Tambah Sekolah</h1>
        <a href="{{ route('sekolah.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
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

    <form action="{{ route('sekolah.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="nama_sekolah" class="block mb-1">Nama Sekolah</label>
            <input id="nama_sekolah" type="text" name="nama_sekolah" value="{{ old('nama_sekolah') }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="alamat" class="block mb-1">Alamat</label>
            <textarea id="alamat" name="alamat" class="w-full border p-2 rounded">{{ old('alamat') }}</textarea>
        </div>

        <div>
            <label for="telepon" class="block mb-1">Telepon</label>
            <input id="telepon" type="text" name="telepon" value="{{ old('telepon') }}" class="w-full border p-2 rounded">
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Simpan
        </button>
    </form>
</div>

</body>
</html>
