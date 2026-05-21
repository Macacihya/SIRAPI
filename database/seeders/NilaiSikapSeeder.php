<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NilaiSikap;

class NilaiSikapSeeder extends Seeder
{
    public function run(): void
    {
        NilaiSikap::create([
            'raport_id' => 1,
            'predikat' => 'A',
            'deskripsi' => 'Menunjukkan sikap disiplin, tanggung jawab, dan kerja sama yang baik.',
        ]);
    }
}