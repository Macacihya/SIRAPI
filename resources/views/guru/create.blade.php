<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Guru</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Tambah Guru</h1>
        <a href="{{ route('guru.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
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

    <form action="{{ route('guru.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="nama" class="block mb-1">Nama</label>
            <input id="nama" type="text" name="nama" value="{{ old('nama') }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="nip" class="block mb-1">NIP</label>
            <input id="nip" type="text" name="nip" value="{{ old('nip') }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="mata_pelajaran" class="block mb-1">Mata Pelajaran</label>
            <input id="mata_pelajaran" type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="sekolah_id" class="block mb-1">Sekolah</label>
            <select id="sekolah_id" name="sekolah_id" class="w-full border p-2 rounded">
                <option value="">Pilih sekolah</option>
                @foreach ($sekolahs as $s)
                    <option value="{{ $s->id }}" @selected(old('sekolah_id') == $s->id)>
                        {{ $s->nama_sekolah }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Simpan
        </button>
    </form>
</div>

</body>
</html>
