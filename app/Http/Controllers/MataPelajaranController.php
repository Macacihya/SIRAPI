<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = MataPelajaran::orderBy('kode_mapel')->get();

        return view('pages.mata-pelajaran.index', compact('mapels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
        ]);

        MataPelajaran::create($validated);

        return redirect()
            ->route('mata-pelajaran')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $kodeMapel)
    {
        $mapel = MataPelajaran::where('kode_mapel', $kodeMapel)->firstOrFail();

        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mapel->update($validated);

        return redirect()
            ->route('mata-pelajaran')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($kodeMapel)
    {
        $mapel = MataPelajaran::where('kode_mapel', $kodeMapel)->firstOrFail();
        $mapel->delete();

        return redirect()
            ->route('mata-pelajaran')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
