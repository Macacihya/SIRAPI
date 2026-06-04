<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        // 2024/2025 Ganjil
        TahunAjaran::updateOrCreate(
            ['tahun_mulai' => 2024, 'tahun_selesai' => 2025, 'semester' => 'Ganjil'],
            ['is_active' => false]
        );

        // 2024/2025 Genap
        TahunAjaran::updateOrCreate(
            ['tahun_mulai' => 2024, 'tahun_selesai' => 2025, 'semester' => 'Genap'],
            ['is_active' => false]
        );

        // 2025/2026 Ganjil
        TahunAjaran::updateOrCreate(
            ['tahun_mulai' => 2025, 'tahun_selesai' => 2026, 'semester' => 'Ganjil'],
            ['is_active' => false]
        );

        // 2025/2026 Genap (Active)
        TahunAjaran::updateOrCreate(
            ['tahun_mulai' => 2025, 'tahun_selesai' => 2026, 'semester' => 'Genap'],
            ['is_active' => true]
        );
    }
}
