<?php

namespace App\Http\Controllers;

use App\Models\CapaianKompetensi;
use App\Models\GuruPengampu;
use App\Models\Nilai;
use Illuminate\Http\Request;

class CapaianKompetensiController extends Controller
{
    public function index()
    {
        $nilaiQuery = Nilai::with(['siswa.kelas', 'raport', 'mataPelajaran']);

        if (getUserRole() === 'guru') {
            $pengampus = GuruPengampu::where('guru_id', auth()->id())->get(['kelas_id', 'mapel_id']);

            $nilaiQuery->where(function ($scoped) use ($pengampus) {
                foreach ($pengampus as $pengampu) {
                    $scoped->orWhere(function ($item) use ($pengampu) {
                        $item->where('mapel_id', $pengampu->mapel_id)
                            ->whereHas('siswa', fn ($siswa) => $siswa->where('kelas_id', $pengampu->kelas_id));
                    });
                }

                if ($pengampus->isEmpty()) {
                    $scoped->whereRaw('1 = 0');
                }
            });
        }

        $nilais = $nilaiQuery->latest()->get();
        $capaianKompetensis = CapaianKompetensi::with('nilai.siswa.kelas')
            ->whereIn('nilai_id', $nilais->pluck('id'))
            ->latest()
            ->get();

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
