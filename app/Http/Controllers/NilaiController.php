<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Raport;
use App\Models\Siswa;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $nilais = Nilai::with(['siswa.kelas', 'raport.tahunAjaran', 'capaianKompetensis'])
            ->latest()
            ->get();
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $raports = Raport::with(['siswa', 'tahunAjaran'])->latest()->get();

        return view('pages.rekap-nilai.index', compact('nilais', 'siswas', 'raports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
            'siswa_id' => 'required|exists:siswas,id',
            'raport_id' => 'required|exists:raports,id',
        ]);

        Nilai::create($validated);

        return redirect()
            ->route('rekap-nilai')
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function update(Request $request, Nilai $nilai)
    {
        $validated = $request->validate([
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
            'siswa_id' => 'required|exists:siswas,id',
            'raport_id' => 'required|exists:raports,id',
        ]);

        $nilai->update($validated);

        return redirect()
            ->route('rekap-nilai')
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()
            ->route('rekap-nilai')
            ->with('success', 'Nilai berhasil dihapus.');
    }
}
