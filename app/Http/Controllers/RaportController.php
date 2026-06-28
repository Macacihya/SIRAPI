<?php

namespace App\Http\Controllers;

use App\Models\Raport;
use App\Models\Ekstrakurikuler;
use App\Models\RekapKehadiran;
use App\Models\Sekolah;
use App\Models\Sikap;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaportController extends Controller
{
    public function index()
    {
        // ini memakai tahun ajaran dan harus diaktifkan untuk di akses
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first()
            ?? TahunAjaran::orderByDesc('tahun_mulai')->first();

        // Wali kelas hanya melihat siswa dari kelas binaannya pada tahun ajaran aktif.
        $siswas = $this->siswaBinaanQuery($tahunAjaranAktif?->id)
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
            'siswa.sekolah',
            'tahunAjaran',
            'nilais.mataPelajaran',
            'nilais.capaianKompetensis',
            'rekapKehadiran',
            'nilaiSikaps.sikap',
            'sikaps',
            'raportEkskuls.ekstrakurikuler',
        ])
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->latest()
            ->get();

        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')->get();
        $sikapMasters = Sikap::orderBy('nama_sikap')->get();
        $ekskulMasters = Ekstrakurikuler::orderBy('nama_eskul')->get();

        return view('pages.rapor.index', compact(
            'raports',
            'siswas',
            'tahunAjarans',
            'sikapMasters',
            'ekskulMasters'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        abort_unless($this->siswaBinaanQuery($validated['tahun_ajaran_id'])->whereKey($validated['siswa_id'])->exists(), 403);

        Raport::firstOrCreate($validated);

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
            'siswa.sekolah',
            'tahunAjaran',
            'nilais.mataPelajaran',
            'nilais.capaianKompetensis',
            'rekapKehadiran',
            'nilaiSikaps.sikap',
            'sikaps',
            'raportEkskuls.ekstrakurikuler',
        ]);

        // Sekolah siswa menjadi sumber identitas, alamat, dan kepala sekolah pada cetak rapor.
        // first() menjadi cadangan untuk instalasi single-school jika siswa lama belum punya sekolah_id.
        $sekolah = $raport->siswa?->sekolah ?? Sekolah::first();

        return view('pages.rapor.lihat', compact('raport', 'sekolah'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        // Cegah wali kelas membuat rapor untuk siswa di luar kelas binaannya pada tahun ajaran terpilih.
        abort_unless($this->siswaBinaanQuery($validated['tahun_ajaran_id'])->whereKey($validated['siswa_id'])->exists(), 403);

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

        abort_unless($this->canAccessRaport($raport), 403);
        abort_unless($this->siswaBinaanQuery($validated['tahun_ajaran_id'])->whereKey($validated['siswa_id'])->exists(), 403);

        $raport->update($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Rapor berhasil diperbarui.');
    }

    public function saveForm(Request $request, Raport $raport)
    {
        abort_unless($this->canAccessRaport($raport), 403);

        $validated = $request->validate([
            'action' => 'required|in:draft,final',
            'sikaps' => 'present|array',
            'sikaps.*.id' => 'required|integer|exists:sikaps,id|distinct',
            'sikaps.*.predikat' => 'nullable|in:A,B,C,D',
            'sikaps.*.deskripsi' => 'nullable|string',
            'eskul' => 'nullable|array',
            'eskul.*.id' => 'required|integer|exists:ekstrakurikulers,id|distinct',
            'eskul.*.predikat' => 'nullable|in:A,B,C,D',
            'eskul.*.deskripsi' => 'nullable|string',
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpha' => 'required|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $raport) {
            // Induk rapor menyimpan status finalisasi dan catatan wali kelas.
            $raport->update([
                'status' => $validated['action'],
                'catatan_wali' => $validated['catatan'] ?? null,
            ]);

            // Rekap kehadiran satu baris per rapor, jadi updateOrCreate aman untuk draft berulang.
            RekapKehadiran::updateOrCreate(
                ['raport_id' => $raport->id],
                [
                    'sakit' => $validated['sakit'],
                    'izin' => $validated['izin'],
                    'alpha' => $validated['alpha'],
                ]
            );

            // Setiap ID master sikap dari admin dipasangkan dengan penilaian siswa ini.
            $sikapSync = collect($validated['sikaps'])
                ->mapWithKeys(fn ($item) => [
                    $item['id'] => [
                        'predikat' => $item['predikat'] ?? null,
                        'deskripsi' => $item['deskripsi'] ?? null,
                    ],
                ])
                ->all();

            $raport->sikaps()->sync($sikapSync);

            // Form mengirim seluruh master ekskul; detail predikat dan catatan tetap per siswa.
            $raport->raportEkskuls()->delete();
            foreach ($validated['eskul'] ?? [] as $eskulData) {
                $raport->raportEkskuls()->create([
                    'ekstrakurikuler_id' => $eskulData['id'],
                    'predikat' => $eskulData['predikat'] ?? null,
                    'deskripsi' => $eskulData['deskripsi'] ?? null,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => $validated['action'] === 'final'
                ? 'Rapor berhasil difinalisasi.'
                : 'Draft rapor berhasil disimpan.',
            'status' => $validated['action'],
        ]);
    }

    public function destroy(Raport $raport)
    {
        abort_unless($this->canAccessRaport($raport), 403);

        $raport->delete();

        return redirect()
            ->route('rapor')
            ->with('success', 'Rapor berhasil dihapus.');
    }

    private function siswaBinaanQuery($tahunAjaranId = null)
    {
        $query = Siswa::query();

        // Scope data berdasarkan user wali kelas yang sedang login.
        if (getUserRole() === 'walikelas') {
            $query->whereHas('kelas', function ($kelas) use ($tahunAjaranId) {
                $kelas->where('wali_guru_id', auth()->id());
                if ($tahunAjaranId) {
                    $kelas->where('tahun_ajaran_id', $tahunAjaranId);
                }
            });
        }

        return $query;
    }

    private function canAccessRaport(Raport $raport): bool
    {
        if (getUserRole() !== 'walikelas') {
            return true;
        }

        // Validasi akses rapor mengikuti scope siswa binaan pada tahun ajaran rapor terkait.
        return $this->siswaBinaanQuery($raport->tahun_ajaran_id)->whereKey($raport->siswa_id)->exists();
    }
}
