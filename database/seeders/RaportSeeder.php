<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Raport;

class RaportSeeder extends Seeder
{
    public function run(): void
    {
        Raport::create([
            'siswa_id' => 1,
            'tahun_ajaran_id' => 1,
        ]);
    }
}