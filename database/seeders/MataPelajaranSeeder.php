<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            ['kode_mapel' => 'BIN',  'nama_mapel' => 'Bahasa Indonesia', 'kkm' => 75],
            ['kode_mapel' => 'MTK',  'nama_mapel' => 'Matematika', 'kkm' => 75],
            ['kode_mapel' => 'IPA',  'nama_mapel' => 'Ilmu Pengetahuan Alam', 'kkm' => 74],
            ['kode_mapel' => 'IPS',  'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'kkm' => 74],
            ['kode_mapel' => 'BING', 'nama_mapel' => 'Bahasa Inggris', 'kkm' => 72],
            ['kode_mapel' => 'PKN',  'nama_mapel' => 'Pendidikan Kewarganegaraan', 'kkm' => 75],
            ['kode_mapel' => 'PAI',  'nama_mapel' => 'Pendidikan Agama Islam', 'kkm' => 75],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani', 'kkm' => 75],
            ['kode_mapel' => 'SBK',  'nama_mapel' => 'Seni Budaya dan Keterampilan', 'kkm' => 75],
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $mapel['kode_mapel']],
                $mapel
            );
        }
    }
}
