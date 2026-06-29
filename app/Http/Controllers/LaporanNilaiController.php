<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\GuruPengampu;
use App\Models\MataPelajaran;
use App\Models\Nilai;

class LaporanNilaiController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->query('search');
        $kelasFilter = $request->query('kelas', 'all');

        // Query utama laporan nilai, lengkap dengan relasi siswa, kelas, mapel, dan tahun ajaran.
        $nilais = Nilai::with([
            'siswa.kelas',
            'raport.tahunAjaran',
            'mataPelajaran',
            'capaianKompetensis',
        ])
            // Wali kelas hanya membaca nilai siswa di kelas binaannya.
            ->when(getUserRole() === 'walikelas', function ($query) {
                $query->whereHas('siswa.kelas', function ($kelas) {
                    $kelas->where('wali_guru_id', auth()->id());
                });
            })
            // Guru mapel hanya membaca nilai dari mapel yang diampunya.
            ->when(getUserRole() === 'guru', function ($query) {
                $pengampus = GuruPengampu::where('guru_id', auth()->id())->get(['kelas_id', 'mapel_id']);

                $query->where(function ($scoped) use ($pengampus) {
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
            })
            ->when($kelasFilter !== 'all', function ($query) use ($kelasFilter) {
                $query->whereHas('siswa.kelas', function ($k) use ($kelasFilter) {
                    $k->where('nama_kelas', $kelasFilter);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('siswa', function ($s) use ($search) {
                    $s->where('nama_siswa', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        if (getUserRole() === 'guru') {
            $pengampus = GuruPengampu::where('guru_id', auth()->id())->get(['kelas_id', 'mapel_id']);
            $kelas = Kelas::whereIn('id', $pengampus->pluck('kelas_id')->unique())->orderBy('nama_kelas')->get();
            $mapels = MataPelajaran::whereIn('kode_mapel', $pengampus->pluck('mapel_id')->unique())->orderBy('nama_mapel')->get();
        } elseif (getUserRole() === 'walikelas') {
            $kelas = Kelas::where('wali_guru_id', auth()->id())->orderBy('nama_kelas')->get();
            $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        } else {
            $kelas = Kelas::orderBy('nama_kelas')->get();
            $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        }

        // Nilai digabung per siswa agar laporan menampilkan rata-rata dan detail mapel.
        $laporanSiswa = $nilais
            ->groupBy('siswa_id')
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'siswa' => $first->siswa,
                    'nilai' => $items,
                    'rata_rata' => round((float) $items->avg('nilai_akhir'), 2),
                    'predikat' => $this->predikat((float) $items->avg('nilai_akhir')),
                ];
            })
            ->sortBy(fn ($item) => $item['siswa']->nama_siswa ?? '')
            ->values();

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $laporanSiswa->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedLaporan = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $laporanSiswa->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]);

        return view('pages.laporan-nilai.index', [
            'laporanSiswa' => $paginatedLaporan, 
            'kelas' => $kelas, 
            'mapels' => $mapels,
            'search' => $search,
            'kelasFilter' => $kelasFilter,
        ]);
    }

    private function predikat(float $nilai): string
    {
        if ($nilai >= 85) {
            return 'A';
        }

        if ($nilai >= 70) {
            return 'B';
        }

        if ($nilai >= 60) {
            return 'C';
        }

        if ($nilai >= 50) {
            return 'D';
        }

        return 'E';
    }
}
