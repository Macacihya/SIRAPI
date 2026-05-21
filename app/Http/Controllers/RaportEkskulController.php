<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\Raport;
use App\Models\RaportEkskul;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RaportEkskulController extends Controller
{
    public function index()
    {
        $raportEkskuls = RaportEkskul::with(['raport.siswa.kelas', 'ekstrakurikuler'])->latest()->get();
        $raports = Raport::with(['siswa', 'tahunAjaran'])->latest()->get();
        $ekstrakurikulers = Ekstrakurikuler::orderBy('nama_eskul')->get();

        return view('pages.rapor.index', compact('raportEkskuls', 'raports', 'ekstrakurikulers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id',
            'ekstrakurikuler_id' => [
                'required',
                'exists:ekstrakurikulers,id',
                Rule::unique('raport_ekskuls', 'ekstrakurikuler_id')
                    ->where('raport_id', $request->input('raport_id')),
            ],
            'predikat' => 'nullable|in:A,B,C,D',
            'keterangan' => 'nullable|string',
        ]);

        RaportEkskul::create($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Ekstrakurikuler rapor berhasil ditambahkan.');
    }

    public function update(Request $request, RaportEkskul $raportEkskul)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id',
            'ekstrakurikuler_id' => [
                'required',
                'exists:ekstrakurikulers,id',
                Rule::unique('raport_ekskuls', 'ekstrakurikuler_id')
                    ->where('raport_id', $request->input('raport_id'))
                    ->ignore($raportEkskul->id),
            ],
            'predikat' => 'nullable|in:A,B,C,D',
            'keterangan' => 'nullable|string',
        ]);

        $raportEkskul->update($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Ekstrakurikuler rapor berhasil diperbarui.');
    }

    public function destroy(RaportEkskul $raportEkskul)
    {
        $raportEkskul->delete();

        return redirect()
            ->route('rapor')
            ->with('success', 'Ekstrakurikuler rapor berhasil dihapus.');
    }
}
