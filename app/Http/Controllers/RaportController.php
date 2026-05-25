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
        // Ambil tahun ajaran aktif sebagai periode default pembuatan rapor.
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first()
            ?? TahunAjaran::orderByDesc('tahun_mulai')->first();

        // Wali kelas hanya melihat siswa dari kelas binaannya.
        $siswas = $this->siswaBinaanQuery()
            ->with('kelas')
            ->orderBy('nama_siswa')
            ->get();

        // Sistem menghasilkan draft rapor otomatis untuk setiap siswa binaan.
        if ($tahunAjaranAktif) {
            foreach ($siswas as $siswa) {
                Raport::firstOrCreate([
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                ]);
            }
        }

        // Data rapor dimuat lengkap untuk kebutuhan kelola, lihat, dan cetak.
        $raports = Raport::with([
            'siswa.kelas',
            'tahunAjaran',
            'nilais.mataPelajaran',
            'nilais.capaianKompetensis',
            'rekapKehadirans',
            'nilaiSikap',
            'raportEkskuls.ekstrakurikuler',
        ])
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->latest()
            ->get();

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

    public function show(Raport $raport)
    {
        abort_unless($this->canAccessRaport($raport), 403);

        // Detail rapor dipakai langsung oleh halaman cetak.
        $raport->load([
            'siswa.kelas',
            'tahunAjaran',
            'nilais.mataPelajaran',
            'nilais.capaianKompetensis',
            'rekapKehadirans',
            'nilaiSikap',
            'raportEkskuls.ekstrakurikuler',
        ]);

        return view('pages.rapor.lihat', compact('raport'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        // Cegah wali kelas membuat rapor untuk siswa di luar kelas binaannya.
        abort_unless($this->siswaBinaanQuery()->whereKey($validated['siswa_id'])->exists(), 403);

        // firstOrCreate menjaga agar satu siswa tidak punya rapor duplikat pada periode yang sama.
        $raport = Raport::firstOrCreate($validated);

        return redirect()
            ->route('rapor.show', $raport)
            ->with('success', 'Rapor berhasil dihasilkan.');
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
        abort_unless($this->canAccessRaport($raport), 403);

        $raport->delete();

        return redirect()
            ->route('rapor')
            ->with('success', 'Rapor berhasil dihapus.');
    }

    private function siswaBinaanQuery()
    {
        $query = Siswa::query();

        // Scope data berdasarkan user wali kelas yang sedang login.
        if (getUserRole() === 'walikelas') {
            $query->whereHas('kelas', function ($kelas) {
                $kelas->where('wali_guru_id', auth()->id());
            });
        }

        return $query;
    }

    private function canAccessRaport(Raport $raport): bool
    {
        if (getUserRole() !== 'walikelas') {
            return true;
        }

        // Validasi akses rapor mengikuti scope siswa binaan.
        return $this->siswaBinaanQuery()->whereKey($raport->siswa_id)->exists();
    }
}
