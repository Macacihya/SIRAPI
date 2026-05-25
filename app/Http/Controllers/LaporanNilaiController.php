<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;

class LaporanNilaiController extends Controller
{
    public function index()
    {
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
                $mapelIds = auth()->user()?->guru?->guruPengampus?->pluck('mapel_id')->filter()->unique();

                if ($mapelIds && $mapelIds->isNotEmpty()) {
                    $query->whereIn('mapel_id', $mapelIds);
                }
            })
            ->latest()
            ->get();

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

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

        return view('pages.laporan-nilai.index', compact('laporanSiswa', 'kelas', 'mapels'));
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
