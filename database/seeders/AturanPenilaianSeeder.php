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
            'Tugas Harian',
            'Ulangan Harian',
            'UTS',
            'UAS',
        ];

        $mapels = MataPelajaran::all();

        foreach ($mapels as $mapel) {
            foreach ($komponenDefault as $komponen) {
                AturanPenilaian::updateOrCreate(
                    ['nama_komponen' => $komponen, 'mapel_id' => $mapel->kode_mapel],
                );
            }
        }
    }
}
