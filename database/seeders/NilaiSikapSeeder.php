<?php

namespace Database\Seeders;

use App\Models\NilaiSikap;
use App\Models\Sikap;
use Illuminate\Database\Seeder;

class NilaiSikapSeeder extends Seeder
{
    public function run(): void
    {
        $spiritual = Sikap::query()->firstWhere('nama_sikap', 'Spiritual');
        $sosial = Sikap::query()->firstWhere('nama_sikap', 'Sosial');

        if (!$spiritual || !$sosial) {
            return;
        }

        NilaiSikap::create([
            'raport_id' => 1,
            'sikap_id' => $spiritual->id,
            'predikat' => 'A',
            'deskripsi' => 'Menunjukkan ketaatan beribadah dan rasa syukur yang sangat baik.',
        ]);

        NilaiSikap::create([
            'raport_id' => 1,
            'sikap_id' => $sosial->id,
            'predikat' => 'A',
            'deskripsi' => 'Menunjukkan sikap disiplin, tanggung jawab, dan kerja sama yang baik.',
        ]);
    }
}
