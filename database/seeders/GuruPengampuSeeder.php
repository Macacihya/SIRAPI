<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\GuruPengampu;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class GuruPengampuSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil guru yang sudah ada di seeder UserSeeder
        $guruTaufik   = Guru::where('nip', '197805122005011004')->first();   // Bahasa Indonesia
        $guruHeryanto = Guru::where('nip', '198804122015031002')->first();   // Matematika

        $kelas6A = Kelas::where('nama_kelas', '6-A')->first();
        $kelas6B = Kelas::where('nama_kelas', '6-B')->first();

        if ($guruTaufik && $kelas6A) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $guruTaufik->user_id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'BIN'],
            );
        }

        if ($guruTaufik && $kelas6B) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $guruTaufik->user_id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'BIN'],
            );
        }

        if ($guruHeryanto && $kelas6A) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $guruHeryanto->user_id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'MTK'],
            );
        }

        if ($guruHeryanto && $kelas6B) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $guruHeryanto->user_id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'MTK'],
            );
        }
    }
}
