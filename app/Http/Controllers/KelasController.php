<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['tahunAjaran', 'siswas'])->get()->map(fn($k) => [
            'id' => $k->id,
            'nama' => $k->nama_kelas,
            'tahun_ajaran_id' => $k->tahun_ajaran_id,
            'tahun_ajaran' => $k->tahunAjaran ? $k->tahunAjaran->tahun_mulai.'/'.$k->tahunAjaran->tahun_selesai.' - '.$k->tahunAjaran->semester : '-',
            'terisi' => $k->siswas->count(),
        ]);
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')->get();

        return view('pages.kelas.index', compact('kelas', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas'      => 'required|string|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        Kelas::create($validated);

        return redirect()
            ->route('kelas')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas'      => 'required|string|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        $kela->update($validated);

        return redirect()
            ->route('kelas')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()
            ->route('kelas')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
