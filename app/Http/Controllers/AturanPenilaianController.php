<?php

namespace App\Http\Controllers;

use App\Models\AturanPenilaian;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class AturanPenilaianController extends Controller
{
    public function index()
    {
        $komponen = AturanPenilaian::with('mataPelajaran')->get();
        $mapels = MataPelajaran::orderBy('kode_mapel')->get();

        return view('pages.aturan-nilai.index', compact('komponen', 'mapels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_komponen' => 'required|string|max:100',
            'mapel_id'      => 'required|exists:mata_pelajarans,kode_mapel',
        ]);

        AturanPenilaian::create($validated);

        return redirect()
            ->route('aturan-nilai')
            ->with('success', 'Komponen penilaian berhasil ditambahkan.');
    }

    public function update(Request $request, AturanPenilaian $aturanPenilaian)
    {
        $validated = $request->validate([
            'nama_komponen' => 'required|string|max:100',
            'mapel_id'      => 'required|exists:mata_pelajarans,kode_mapel',
        ]);

        $aturanPenilaian->update($validated);

        return redirect()
            ->route('aturan-nilai')
            ->with('success', 'Komponen penilaian berhasil diperbarui.');
    }

    public function destroy(AturanPenilaian $aturanPenilaian)
    {
        $aturanPenilaian->delete();

        return redirect()
            ->route('aturan-nilai')
            ->with('success', 'Komponen penilaian berhasil dihapus.');
    }
}
