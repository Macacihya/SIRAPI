<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\RiwayatStatusSiswa;
use Illuminate\Database\Seeder;

class RiwayatStatusSiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = Siswa::all();

        foreach ($siswas as $siswa) {
            RiwayatStatusSiswa::updateOrCreate(
                ['siswa_id' => $siswa->id, 'status' => 'Aktif'],
            );
        }
    }
}
