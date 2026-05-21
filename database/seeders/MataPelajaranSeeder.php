<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            ['kode_mapel' => 'BIN',  'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'MTK',  'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'IPA',  'nama_mapel' => 'Ilmu Pengetahuan Alam'],
            ['kode_mapel' => 'IPS',  'nama_mapel' => 'Ilmu Pengetahuan Sosial'],
            ['kode_mapel' => 'BING', 'nama_mapel' => 'Bahasa Inggris'],
            ['kode_mapel' => 'PKN',  'nama_mapel' => 'Pendidikan Kewarganegaraan'],
            ['kode_mapel' => 'PAI',  'nama_mapel' => 'Pendidikan Agama Islam'],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani'],
            ['kode_mapel' => 'SBK',  'nama_mapel' => 'Seni Budaya dan Keterampilan'],
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $mapel['kode_mapel']],
                $mapel
            );
        }
    }
}
