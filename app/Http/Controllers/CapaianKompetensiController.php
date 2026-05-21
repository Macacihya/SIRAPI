<?php

namespace App\Http\Controllers;

use App\Models\CapaianKompetensi;
use App\Models\Nilai;
use Illuminate\Http\Request;

class CapaianKompetensiController extends Controller
{
    public function index()
    {
        $capaianKompetensis = CapaianKompetensi::with('nilai.siswa.kelas')->latest()->get();
        $nilais = Nilai::with(['siswa', 'raport'])->latest()->get();

        return view('pages.capaian-kompetensi.index', compact('capaianKompetensis', 'nilais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nilai_id' => 'required|exists:nilais,id',
        ]);

        CapaianKompetensi::create($validated);

        return redirect()
            ->route('capaian-kompetensi')
            ->with('success', 'Capaian kompetensi berhasil ditambahkan.');
    }

    public function update(Request $request, CapaianKompetensi $capaianKompetensi)
    {
        $validated = $request->validate([
            'nilai_id' => 'required|exists:nilais,id',
        ]);

        $capaianKompetensi->update($validated);

        return redirect()
            ->route('capaian-kompetensi')
            ->with('success', 'Capaian kompetensi berhasil diperbarui.');
    }

    public function destroy(CapaianKompetensi $capaianKompetensi)
    {
        $capaianKompetensi->delete();

        return redirect()
            ->route('capaian-kompetensi')
            ->with('success', 'Capaian kompetensi berhasil dihapus.');
    }
}
