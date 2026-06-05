<?php

namespace App\Http\Controllers;

use App\Models\AturanPenilaian;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class AturanPenilaianController extends Controller
{
    public function index()
    {
        $komponen = AturanPenilaian::with('mataPelajaran')->get()->map(fn($k) => [
            'id'       => $k->id,
            'nama'     => $k->nama_komponen,
            'bobot'    => (float) $k->bobot,
            'mapel'    => $k->mataPelajaran->nama_mapel ?? '-',
            'mapel_id' => $k->mapel_id,
        ])->values();
        $mapels = MataPelajaran::orderBy('kode_mapel')->get();

        // KKM default dari mapel pertama (untuk preview)
        $defaultKkm = $mapels->first()->kkm ?? 70;

        return view('pages.aturan-nilai.index', compact('komponen', 'mapels', 'defaultKkm'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_komponen' => 'required|string|max:100',
            'bobot'         => 'required|numeric|min:0|max:100',
            'mapel_id'      => 'required|exists:mata_pelajarans,kode_mapel',
        ]);

        // Batasi maksimal 3 komponen per mata pelajaran
        $count = AturanPenilaian::where('mapel_id', $validated['mapel_id'])->count();
        if ($count >= 3) {
            return redirect()
                ->route('aturan-nilai')
                ->with('error', 'Maksimal 3 komponen penilaian per mata pelajaran.');
        }

        AturanPenilaian::create($validated);

        return redirect()
            ->route('aturan-nilai')
            ->with('success', 'Komponen penilaian berhasil ditambahkan.');
    }

    public function update(Request $request, AturanPenilaian $aturanPenilaian)
    {
        $validated = $request->validate([
            'nama_komponen' => 'required|string|max:100',
            'bobot'         => 'required|numeric|min:0|max:100',
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

    public function destroyAll()
    {
        AturanPenilaian::truncate();

        return redirect()
            ->route('aturan-nilai')
            ->with('success', 'Semua komponen penilaian berhasil dihapus.');
    }
}
