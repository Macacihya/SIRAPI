<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Raport;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        // Ambil seluruh nilai beserta konteks siswa, kelas, rapor, dan mapel.
        $nilais = Nilai::with(['siswa.kelas', 'raport.tahunAjaran', 'mataPelajaran', 'capaianKompetensis'])
            ->latest()
            ->get();
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $raports = Raport::with(['siswa', 'tahunAjaran'])->latest()->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        $kelas = Kelas::with('siswas')->orderBy('nama_kelas')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')->get();

        // Rekap kelas dihitung dari kumpulan nilai yang sudah dimuat.
        $rekapKelas = $kelas->map(function ($kelasItem) use ($nilais) {
            $kelasNilais = $nilais->where('siswa.kelas_id', $kelasItem->id);

            return [
                'kelas' => $kelasItem,
                'jumlah_siswa' => $kelasItem->siswas->count(),
                'jumlah_nilai' => $kelasNilais->count(),
                'rata_rata' => round((float) $kelasNilais->avg('nilai_akhir'), 2),
                'tertinggi' => round((float) $kelasNilais->max('nilai_akhir'), 2),
                'terendah' => round((float) $kelasNilais->min('nilai_akhir'), 2),
            ];
        });

        return view('pages.rekap-nilai.index', compact('nilais', 'siswas', 'raports', 'mapels', 'tahunAjarans', 'rekapKelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
            'siswa_id' => 'required|exists:siswas,id',
            'raport_id' => 'required|exists:raports,id',
            'mapel_id' => 'nullable|exists:mata_pelajarans,kode_mapel',
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
            'mapel_id' => 'nullable|exists:mata_pelajarans,kode_mapel',
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
