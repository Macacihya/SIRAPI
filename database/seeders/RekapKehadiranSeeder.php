<?php

namespace Database\Seeders;

use App\Models\RekapKehadiran;
use App\Models\Raport;
use Illuminate\Database\Seeder;

class RekapKehadiranSeeder extends Seeder
{
    public function run(): void
    {
        $raports = Raport::all();

        foreach ($raports as $raport) {
            RekapKehadiran::updateOrCreate([
                'raport_id' => $raport->id,
            ], [
                'sakit' => rand(0, 3),
                'izin' => rand(0, 2),
                'alpha' => 0,
            ]);
        }
    }
}
