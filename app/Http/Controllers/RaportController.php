<?php

namespace App\Http\Controllers;

use App\Models\Raport;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class RaportController extends Controller
{
    public function index()
    {
        $raports = Raport::with([
            'siswa.kelas',
            'tahunAjaran',
            'nilais.capaianKompetensis',
            'rekapKehadiran',
            'nilaiSikaps.sikap',
            'sikaps',
            'raportEkskuls.ekstrakurikuler',
        ])->latest()->get();
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')->get();

        return view('pages.rapor.index', compact('raports', 'siswas', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        Raport::create($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Rapor berhasil ditambahkan.');
    }

    public function update(Request $request, Raport $raport)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        $raport->update($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Rapor berhasil diperbarui.');
    }

    public function destroy(Raport $raport)
    {
        $raport->delete();

        return redirect()
            ->route('rapor')
            ->with('success', 'Rapor berhasil dihapus.');
    }
}
