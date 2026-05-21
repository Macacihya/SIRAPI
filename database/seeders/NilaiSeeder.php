<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nilai;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        Nilai::create([
            'nilai_akhir' => 88.50,
            'siswa_id' => 1,
            'raport_id' => 1,
        ]);
    }
}