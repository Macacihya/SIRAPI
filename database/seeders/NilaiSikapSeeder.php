<?php

namespace Database\Seeders;

use App\Models\NilaiSikap;
use App\Models\Sikap;
use App\Models\Raport;
use Illuminate\Database\Seeder;

class NilaiSikapSeeder extends Seeder
{
    public function run(): void
    {
        $spiritual = Sikap::query()->firstWhere('nama_sikap', 'Spiritual');
        $sosial = Sikap::query()->firstWhere('nama_sikap', 'Sosial');
        $raports = Raport::all();

        foreach ($raports as $raport) {
            if ($spiritual) {
                NilaiSikap::updateOrCreate([
                    'raport_id' => $raport->id,
                    'sikap_id' => $spiritual->id,
                ], [
                    'predikat' => 'A',
                    'deskripsi' => 'Menunjukkan ketaatan beribadah dan rasa syukur yang sangat baik dalam keseharian di sekolah.',
                ]);
            }

            if ($sosial) {
                NilaiSikap::updateOrCreate([
                    'raport_id' => $raport->id,
                    'sikap_id' => $sosial->id,
                ], [
                    'predikat' => 'A',
                    'deskripsi' => 'Menunjukkan sikap disiplin, tanggung jawab, kerja sama, dan sopan santun yang sangat konsisten.',
                ]);
            }
        }
    }
}
