<?php

namespace Database\Seeders;

use App\Models\RekapKehadiran;
use Illuminate\Database\Seeder;

class RekapKehadiranSeeder extends Seeder
{
    public function run(): void
    {
        RekapKehadiran::create([
            'raport_id' => 1,
            'sakit' => 2,
            'izin' => 1,
            'alpha' => 0,
        ]);
    }
}
