<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Raport;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $raport = Raport::with('siswa')->firstOrFail();
        $mapel = MataPelajaran::where('kode_mapel', 'BIN')->firstOrFail();

        $nilaiUh = 87.00;
        $nilaiUts = 89.00;
        $nilaiUas = 88.50;

        Nilai::updateOrCreate(
            [
                'siswa_id' => $raport->siswa_id,
                'raport_id' => $raport->id,
                'mapel_id' => $mapel->kode_mapel,
            ],
            [
                'nilai_uh' => $nilaiUh,
                'nilai_uts' => $nilaiUts,
                'nilai_uas' => $nilaiUas,
                'nilai_akhir' => round(($nilaiUh * 0.30) + ($nilaiUts * 0.30) + ($nilaiUas * 0.40), 2),
                'status' => 'final',
            ]
        );
    }
}
