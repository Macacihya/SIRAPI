<?php

namespace Database\Seeders;

use App\Models\AturanPenilaian;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class AturanPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        $komponenDefault = [
            'Ulangan Harian' => 30,
            'UTS' => 30,
            'UAS' => 40,
        ];

        $mapels = MataPelajaran::all();

        foreach ($mapels as $mapel) {
            foreach ($komponenDefault as $komponen => $bobot) {
                AturanPenilaian::updateOrCreate(
                    ['nama_komponen' => $komponen, 'mapel_id' => $mapel->kode_mapel],
                    ['bobot' => $bobot]
                );
            }
        }
    }
}
