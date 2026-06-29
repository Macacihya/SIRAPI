<?php

namespace App\Http\Controllers;

use App\Models\CapaianKompetensi;
use App\Models\GuruPengampu;
use App\Models\Nilai;
use App\Models\Raport;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class CapaianKompetensiController extends Controller
{
    public function index(Request $request)
    {
        $role = getUserRole();
        $userId = auth()->id();
        
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')->get();
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        $selectedTahunAjaranId = $request->get('tahun_ajaran_id', $tahunAjaranAktif?->id ?? $tahunAjarans->first()?->id);

        if ($role === 'guru') {
            $pengampus = GuruPengampu::whereHas('kelas', function ($q) use ($selectedTahunAjaranId) {
                    if ($selectedTahunAjaranId) {
                        $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
                    }
                })
                ->with(['kelas', 'mataPelajaran'])
                ->where('guru_id', $userId)
                ->get();
        } else {
            $pengampus = GuruPengampu::whereHas('kelas', function ($q) use ($selectedTahunAjaranId) {
                    if ($selectedTahunAjaranId) {
                        $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
                    }
                })
                ->with(['kelas', 'mataPelajaran'])
                ->get();
        }

        $kelasList = $pengampus->pluck('kelas')->filter()->unique('id')->values();
        $mapelList = $pengampus->pluck('mataPelajaran')->filter()->unique('kode_mapel')->values();

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

        $studentsData = [];
        if ($siswas->isNotEmpty() && $selectedTahunAjaranId) {
            foreach ($siswas as $siswa) {
                // Cari raport
                $raport = Raport::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $selectedTahunAjaranId)
                    ->first();

                $nilai = null;
                $capaian = null;
                if ($raport && $selectedMapelId) {
                    $nilai = Nilai::where('siswa_id', $siswa->id)
                        ->where('raport_id', $raport->id)
                        ->where('mapel_id', $selectedMapelId)
                        ->first();
                        
                    if ($nilai) {
                        $capaian = CapaianKompetensi::where('nilai_id', $nilai->id)->first();
                    }
                }

                $initials = collect(explode(' ', $siswa->nama_siswa))
                    ->take(2)
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->implode('');
                
                $status = 'Belum';
                if ($capaian) {
                    $status = ucfirst($capaian->status);
                }

                $studentsData[] = [
                    'id' => $siswa->id,
                    'nilai_id' => $nilai?->id, // Jika null, belum dinilai
                    'kelas' => $siswa->kelas->nama_kelas ?? '-',
                    'nis' => $siswa->nis ?? $siswa->nisn ?? '-',
                    'init' => $initials,
                    'nama' => $siswa->nama_siswa,
                    'capaian' => $capaian?->deskripsi ?? '',
                    'status' => $status,
                ];
            }
        }

        $selectedMapel = MataPelajaran::where('kode_mapel', $selectedMapelId)->first();

        return view('pages.capaian-kompetensi.index', compact(
            'kelasList',
            'mapelList',
            'tahunAjarans',
            'selectedKelasId',
            'selectedMapelId',
            'selectedTahunAjaranId',
            'selectedMapel',
            'studentsData'
        ));
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'capaian' => 'nullable|array',
            'capaian.*.nilai_id' => 'required|exists:nilais,id',
            'capaian.*.deskripsi' => 'nullable|string',
            'action' => 'required|in:draft,final',
        ]);

        $action = $request->action;
        $capaians = $request->capaian ?? [];

        if (empty($capaians)) {
            return redirect()->back()->with('error', 'Tidak ada data nilai siswa yang dapat diproses.');
        }

        // Validasi kelengkapan jika finalisasi
        if ($action === 'final') {
            foreach ($capaians as $data) {
                if (empty(trim($data['deskripsi'] ?? ''))) {
                    return redirect()->back()->with('error', 'Gagal finalisasi. Semua deskripsi capaian siswa harus terisi lengkap.');
                }
            }
        }

        foreach ($capaians as $data) {
            if (isset($data['nilai_id'])) {
                if (empty(trim($data['deskripsi'] ?? '')) && $action === 'draft') {
                    $existing = CapaianKompetensi::where('nilai_id', $data['nilai_id'])->first();
                    if (!$existing) {
                        continue;
                    }
                }

                CapaianKompetensi::updateOrCreate(
                    ['nilai_id' => $data['nilai_id']],
                    [
                        'deskripsi' => $data['deskripsi'] ?? '',
                        'status' => $action,
                    ]
                );
            }
        }

        $msg = $action === 'final' ? 'Capaian kompetensi berhasil difinalisasi.' : 'Draft capaian kompetensi berhasil disimpan.';
        return redirect()->back()->with('success', $msg);
    }
}
