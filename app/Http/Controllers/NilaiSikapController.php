<?php

namespace App\Http\Controllers;

use App\Models\NilaiSikap;
use App\Models\Raport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NilaiSikapController extends Controller
{
    public function index()
    {
        $nilaiSikaps = NilaiSikap::with('raport.siswa.kelas')->latest()->get();
        $raports = Raport::with(['siswa', 'tahunAjaran'])->latest()->get();

        return view('pages.rapor.index', compact('nilaiSikaps', 'raports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id|unique:nilai_sikaps,raport_id',
            'predikat' => 'nullable|in:A,B,C,D',
            'deskripsi' => 'nullable|string',
        ]);

        NilaiSikap::create($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Nilai sikap berhasil ditambahkan.');
    }

    public function update(Request $request, NilaiSikap $nilaiSikap)
    {
        $validated = $request->validate([
            'raport_id' => [
                'required',
                'exists:raports,id',
                Rule::unique('nilai_sikaps', 'raport_id')->ignore($nilaiSikap->id),
            ],
            'predikat' => 'nullable|in:A,B,C,D',
            'deskripsi' => 'nullable|string',
        ]);

        $nilaiSikap->update($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Nilai sikap berhasil diperbarui.');
    }

    public function destroy(NilaiSikap $nilaiSikap)
    {
        $nilaiSikap->delete();

        return redirect()
            ->route('rapor')
            ->with('success', 'Nilai sikap berhasil dihapus.');
    }
}
