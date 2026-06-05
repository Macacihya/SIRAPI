<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Raport;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\GuruPengampu;
use App\Models\AturanPenilaian;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Rekap Nilai — menampilkan ringkasan nilai per kelas.
     * Guru hanya melihat kelas yang diajarnya (GuruPengampu).
     * Admin melihat semua kelas.
     */
    public function index()
    {
        $role = getUserRole();
        $userId = auth()->id();

        // Ambil kelas dan mapel yang diampu guru (untuk filtering)
        $guruKelasIds = [];
        $guruPengampus = collect();
        if ($role === 'guru') {
            $guruPengampus = GuruPengampu::where('guru_id', $userId)->get(['kelas_id', 'mapel_id']);
            $guruKelasIds = $guruPengampus
                ->pluck('kelas_id')
                ->unique()
                ->toArray();
        }

        // Query nilai
        $nilaisQuery = Nilai::with(['siswa.kelas', 'raport.tahunAjaran', 'mataPelajaran', 'capaianKompetensis']);

        if ($role === 'guru' && $guruPengampus->isNotEmpty()) {
            $nilaisQuery->where(function ($scoped) use ($guruPengampus) {
                foreach ($guruPengampus as $pengampu) {
                    $scoped->orWhere(function ($item) use ($pengampu) {
                        $item->where('mapel_id', $pengampu->mapel_id)
                            ->whereHas('siswa', fn ($siswa) => $siswa->where('kelas_id', $pengampu->kelas_id));
                    });
                }
            });
        } elseif ($role === 'guru' && $guruPengampus->isEmpty()) {
            // Guru tidak mengampu kelas apapun, kosongkan
            $nilaisQuery->whereRaw('1 = 0');
        }

        $nilais = $nilaisQuery->latest()->get();

        // Ambil kelas — filter untuk guru
        if ($role === 'guru' && !empty($guruKelasIds)) {
            $kelas = Kelas::with('siswas')->whereIn('id', $guruKelasIds)->orderBy('nama_kelas')->get();
        } elseif ($role === 'walikelas') {
            $kelas = Kelas::with('siswas')->where('wali_guru_id', $userId)->orderBy('nama_kelas')->get();
        } else {
            $kelas = Kelas::with('siswas')->orderBy('nama_kelas')->get();
        }

        // Ambil siswa & rapor sesuai scope kelas yang sudah ditentukan
        $kelasIdsScoped = $kelas->pluck('id');
        $siswas = Siswa::with('kelas')
            ->when($kelasIdsScoped->isNotEmpty(), fn ($q) => $q->whereIn('kelas_id', $kelasIdsScoped))
            ->orderBy('nama_siswa')
            ->get();
        $siswaIdsScoped = $siswas->pluck('id');
        $raports = Raport::with(['siswa', 'tahunAjaran'])
            ->when($siswaIdsScoped->isNotEmpty(), fn ($q) => $q->whereIn('siswa_id', $siswaIdsScoped))
            ->latest()->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')->get();

        // Rekap kelas
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

    /**
     * Halaman Penilaian — Guru menginput nilai per kelas & mapel.
     * Data diambil dari database berdasarkan guru yang login.
     */
    public function penilaian(Request $request)
    {
        $role = getUserRole();
        $userId = auth()->id();

        // Tahun ajaran aktif
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')->get();
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        $selectedTahunAjaranId = $request->get('tahun_ajaran_id', $tahunAjaranAktif?->id ?? $tahunAjarans->first()?->id);

        // Data untuk guru: kelas & mapel yang diampu pada tahun ajaran terpilih
        $pengampus = GuruPengampu::whereHas('kelas', function ($q) use ($selectedTahunAjaranId) {
                if ($selectedTahunAjaranId) {
                    $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
                }
            })
            ->with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $userId)
            ->get();

        $kelasList = $pengampus->pluck('kelas')->filter()->unique('id')->values();
        $mapelList = $pengampus->pluck('mataPelajaran')->filter()->unique('kode_mapel')->values();

        // Default filter dari request atau set default
        $selectedKelasId = $request->get('kelas_id', $kelasList->first()?->id);
        $selectedMapelId = $request->get('mapel_id', $mapelList->first()?->kode_mapel);

        // Ambil siswa dari kelas terpilih
        $siswas = collect();
        if ($selectedKelasId) {
            $siswas = Siswa::where('kelas_id', $selectedKelasId)
                ->where('status_aktif', true)
                ->orderBy('nama_siswa')
                ->get();
        }

        // Ambil raport per siswa untuk tahun ajaran terpilih
        $grades = [];
        if ($siswas->isNotEmpty() && $selectedTahunAjaranId) {
            foreach ($siswas as $siswa) {
                // Cari raport siswa ini
                $raport = Raport::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $selectedTahunAjaranId)
                    ->first();

                // Cari nilai yang sudah ada
                $nilai = null;
                if ($raport && $selectedMapelId) {
                    $nilai = Nilai::where('siswa_id', $siswa->id)
                        ->where('raport_id', $raport->id)
                        ->where('mapel_id', $selectedMapelId)
                        ->first();
                }

                $initials = collect(explode(' ', $siswa->nama_siswa))
                    ->take(2)
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->implode('');

                $grades[] = [
                    'siswa_id' => $siswa->id,
                    'raport_id' => $raport?->id,
                    'nilai_id' => $nilai?->id,
                    'nis' => $siswa->nis ?? $siswa->nisn ?? '-',
                    'init' => $initials,
                    'nama' => $siswa->nama_siswa,
                    'uh' => $nilai?->nilai_uh,
                    'uts' => $nilai?->nilai_uts,
                    'uas' => $nilai?->nilai_uas,
                    'nilai_akhir' => $nilai?->nilai_akhir,
                    'status' => $nilai?->status ?? 'belum',
                ];
            }
        }

        // Ambil nama mapel terpilih
        $selectedMapel = MataPelajaran::where('kode_mapel', $selectedMapelId)->first();

        // KKM dari mata pelajaran (default 70 jika belum diatur)
        $kkm = $selectedMapel->kkm ?? 70;

        // Ambil bobot dari aturan penilaian. Wajib 3 komponen dan total 100%.
        $aturanBobot = null;
        if ($selectedMapelId) {
            $aturans = AturanPenilaian::where('mapel_id', $selectedMapelId)
                ->orderBy('id')
                ->get();
            $totalBobot = $aturans->sum('bobot');

            if ($aturans->count() === 3 && $totalBobot == 100) {
                $aturanBobot = [
                    'uh' => ['nama' => $aturans[0]->nama_komponen, 'bobot' => (float) $aturans[0]->bobot],
                    'uts' => ['nama' => $aturans[1]->nama_komponen, 'bobot' => (float) $aturans[1]->bobot],
                    'uas' => ['nama' => $aturans[2]->nama_komponen, 'bobot' => (float) $aturans[2]->bobot],
                ];
            }
        }

        return view('pages.penilaian.index', compact(
            'kelasList',
            'mapelList',
            'tahunAjarans',
            'tahunAjaranAktif',
            'grades',
            'selectedKelasId',
            'selectedMapelId',
            'selectedTahunAjaranId',
            'selectedMapel',
            'aturanBobot',
            'kkm',
            'role'
        ));
    }

    /**
     * Simpan batch nilai (draft atau final).
     * Menerima array nilai dari form penilaian guru.
     */
    public function storeBatch(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.siswa_id' => 'required|exists:siswas,id',
            'grades.*.uh' => 'nullable|numeric|min:0|max:100',
            'grades.*.uts' => 'nullable|numeric|min:0|max:100',
            'grades.*.uas' => 'nullable|numeric|min:0|max:100',
            'mapel_id' => 'required|exists:mata_pelajarans,kode_mapel',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'action' => 'required|in:draft,final',
        ]);

        $mapelId = $request->mapel_id;
        $tahunAjaranId = $request->tahun_ajaran_id;
        $action = $request->action;

        // Ambil bobot dari aturan penilaian
        $aturans = AturanPenilaian::where('mapel_id', $mapelId)
            ->orderBy('id')
            ->get();
        $totalBobot = $aturans->sum('bobot');

        if ($aturans->count() !== 3 || $totalBobot != 100) {
            return redirect()->back()->with('error', 'Gagal menyimpan nilai. Aturan penilaian untuk mapel ini belum lengkap atau total bobot tidak 100%.');
        }

        $bobotUh = (float) $aturans[0]->bobot / 100;
        $bobotUts = (float) $aturans[1]->bobot / 100;
        $bobotUas = (float) $aturans[2]->bobot / 100;

        foreach ($request->grades as $gradeData) {
            $siswaId = $gradeData['siswa_id'];
            $uh = isset($gradeData['uh']) && $gradeData['uh'] !== '' ? (float) $gradeData['uh'] : null;
            $uts = isset($gradeData['uts']) && $gradeData['uts'] !== '' ? (float) $gradeData['uts'] : null;
            $uas = isset($gradeData['uas']) && $gradeData['uas'] !== '' ? (float) $gradeData['uas'] : null;

            // Hitung nilai akhir berdasarkan bobot dari aturan penilaian
            $nilaiAkhir = null;
            if ($uh !== null || $uts !== null || $uas !== null) {
                $nilaiAkhir = (($uh ?? 0) * $bobotUh) + (($uts ?? 0) * $bobotUts) + (($uas ?? 0) * $bobotUas);
                $nilaiAkhir = round($nilaiAkhir, 2);
            }

            // Cari atau buat raport
            $raport = Raport::firstOrCreate(
                ['siswa_id' => $siswaId, 'tahun_ajaran_id' => $tahunAjaranId]
            );

            // Upsert nilai
            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'raport_id' => $raport->id,
                    'mapel_id' => $mapelId,
                ],
                [
                    'nilai_uh' => $uh,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'status' => $action,
                ]
            );
        }

        $statusLabel = $action === 'final' ? 'difinalisasi' : 'disimpan sebagai draft';

        return redirect()
            ->route('penilaian', [
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $mapelId,
                'tahun_ajaran_id' => $tahunAjaranId,
            ])
            ->with('success', "Nilai berhasil {$statusLabel}.");
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
