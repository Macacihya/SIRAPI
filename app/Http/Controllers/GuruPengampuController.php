<?php

namespace App\Http\Controllers;

use App\Models\GuruPengampu;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class GuruPengampuController extends Controller
{
    public function index()
    {
        $pengampus = GuruPengampu::with(['guru.user', 'kelas', 'mataPelajaran'])->get();
        $gurus  = Guru::with('user')->get();
        $kelas  = Kelas::all();
        $mapels = MataPelajaran::all();

        return view('pages.guru-pengampu.index', compact('pengampus', 'gurus', 'kelas', 'mapels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id'  => 'required|exists:gurus,user_id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajarans,kode_mapel',
        ]);

        GuruPengampu::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Guru pengampu berhasil ditambahkan.');
    }

    public function destroy(GuruPengampu $guruPengampu)
    {
        $guruPengampu->delete();

        return redirect()
            ->back()
            ->with('success', 'Guru pengampu berhasil dihapus.');
    }
}
