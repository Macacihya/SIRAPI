<?php

namespace App\Http\Controllers;

use App\Models\AturanPenilaian;
use App\Models\Ekstrakurikuler;
use App\Models\MataPelajaran;
use App\Models\Sikap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Master ini menjadi sumber field dinamis pada form rapor wali kelas.
        $sikaps = Sikap::orderBy('nama_sikap')->get(['id', 'nama_sikap']);
        $ekstrakurikulers = Ekstrakurikuler::orderBy('nama_eskul')->get(['id', 'nama_eskul']);

        return view('pages.aturan-nilai.index', compact(
            'komponen',
            'mapels',
            'defaultKkm',
            'sikaps',
            'ekstrakurikulers'
        ));
    }

    public function syncRaporMasters(Request $request)
    {
        $validated = $request->validate([
            'sikaps' => 'present|array',
            'sikaps.*.id' => 'nullable|integer',
            'sikaps.*.nama' => 'required|string|max:100|distinct:ignore_case',
            'ekskuls' => 'present|array',
            'ekskuls.*.id' => 'nullable|integer',
            'ekskuls.*.nama' => 'required|string|max:100|distinct:ignore_case',
        ]);

        DB::transaction(function () use ($validated) {
            $sikapIds = collect($validated['sikaps'])->map(function ($item) {
                $sikap = !empty($item['id']) ? Sikap::find($item['id']) : null;
                $sikap ??= new Sikap();
                $sikap->nama_sikap = trim($item['nama']);
                $sikap->save();

                return $sikap->id;
            });

            $ekskulIds = collect($validated['ekskuls'])->map(function ($item) {
                $ekskul = !empty($item['id']) ? Ekstrakurikuler::find($item['id']) : null;
                $ekskul ??= new Ekstrakurikuler();
                $ekskul->nama_eskul = trim($item['nama']);
                $ekskul->save();

                return $ekskul->id;
            });

            Sikap::whereNotIn('id', $sikapIds)->delete();
            Ekstrakurikuler::whereNotIn('id', $ekskulIds)->delete();
        });

        return response()->json([
            'message' => 'Daftar sikap dan ekstrakurikuler berhasil disimpan.',
            'sikaps' => Sikap::orderBy('nama_sikap')->get()->map(fn ($item) => [
                'id' => $item->id,
                'nama' => $item->nama_sikap,
            ]),
            'ekskuls' => Ekstrakurikuler::orderBy('nama_eskul')->get()->map(fn ($item) => [
                'id' => $item->id,
                'nama' => $item->nama_eskul,
            ]),
        ]);
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
