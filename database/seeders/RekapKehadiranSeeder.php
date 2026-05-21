<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekapKehadiran;

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