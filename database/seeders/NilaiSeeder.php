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
        $raports = Raport::all();
        $mapels = MataPelajaran::all();

        // Sample scores to look realistic
        $scoreConfigs = [
            'BIN'  => [80, 85, 82],
            'MTK'  => [78, 80, 84],
            'IPA'  => [82, 85, 80],
            'IPS'  => [85, 82, 88],
            'BING' => [75, 78, 80],
            'PKN'  => [88, 86, 90],
            'PAI'  => [90, 92, 91],
            'PJOK' => [85, 88, 86],
            'SBK'  => [82, 84, 85],
        ];

        foreach ($raports as $raport) {
            foreach ($mapels as $mapel) {
                $code = $mapel->kode_mapel;
                $config = $scoreConfigs[$code] ?? [80, 80, 80];
                
                $uh = (int) ($config[0] + rand(-5, 5));
                $uts = (int) ($config[1] + rand(-5, 5));
                $uas = (int) ($config[2] + rand(-5, 5));
                $akhir = (int) round(($uh * 0.5) + ($uts * 0.25) + ($uas * 0.25));

                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $raport->siswa_id,
                        'raport_id' => $raport->id,
                        'mapel_id' => $code,
                    ],
                    [
                        'nilai_uh' => $uh,
                        'nilai_uts' => $uts,
                        'nilai_uas' => $uas,
                        'nilai_akhir' => $akhir,
                        'status' => 'draft',
                    ]
                );
            }
        }
    }
}
