<?php

namespace Database\Seeders;

use App\Models\Sikap;
use Illuminate\Database\Seeder;

class SikapSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Spiritual', 'Sosial'] as $namaSikap) {
            Sikap::query()->firstOrCreate([
                'nama_sikap' => $namaSikap,
            ]);
        }
    }
}
