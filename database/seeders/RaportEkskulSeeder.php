<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RaportEkskul;

class RaportEkskulSeeder extends Seeder
{
    public function run(): void
    {
        RaportEkskul::create([
            'raport_id' => 1,
            'ekstrakurikuler_id' => 1,
            'deskripsi' => 'Aktif mengikuti kegiatan ekstrakurikuler badminton.',
        ]);
    }
}
