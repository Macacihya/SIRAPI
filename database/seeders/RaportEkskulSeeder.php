<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RaportEkskul;
use App\Models\Raport;
use App\Models\Ekstrakurikuler;

class RaportEkskulSeeder extends Seeder
{
    public function run(): void
    {
        $raports = Raport::all();
        $ekskuls = Ekstrakurikuler::all();

        if ($ekskuls->isEmpty()) {
            return;
        }

        foreach ($raports as $index => $raport) {
            $ekskul = $ekskuls[$index % $ekskuls->count()];

            RaportEkskul::updateOrCreate([
                'raport_id' => $raport->id,
                'ekstrakurikuler_id' => $ekskul->id,
            ], [
                'deskripsi' => 'Menunjukkan minat dan partisipasi yang aktif dalam kegiatan ekstrakurikuler ' . $ekskul->nama_eskul . '.',
            ]);
        }
    }
}
