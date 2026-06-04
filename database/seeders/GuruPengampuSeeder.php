<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\GuruPengampu;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuruPengampuSeeder extends Seeder
{
    public function run(): void
    {
        $kelas6A = Kelas::where('nama_kelas', '6-A')->first();
        $kelas6B = Kelas::where('nama_kelas', '6-B')->first();

        if (!$kelas6A || !$kelas6B) {
            return;
        }

        // Ambil data User untuk pencarian ID
        $taufikUser   = User::where('username', 'taufik')->first();   // Wali 6-A, mengajar BIN di 6-B
        $linaUser     = User::where('username', 'lina')->first();     // Mengajar BIN di 6-A
        $heryantoUser = User::where('username', 'heryanto')->first(); // Wali 6-B, mengajar MTK di 6-A
        $budiUser     = User::where('username', 'budi')->first();     // Mengajar MTK di 6-B
        $amranUser    = User::where('username', 'amran')->first();    // IPA
        $sitiUser     = User::where('username', 'siti')->first();     // IPS
        $johnUser     = User::where('username', 'john')->first();     // BING
        $pujiUser     = User::where('username', 'puji')->first();     // PKN
        $hasanUser    = User::where('username', 'hasan')->first();    // PAI
        $wawanUser    = User::where('username', 'wawan')->first();    // PJOK
        $endahUser    = User::where('username', 'endah')->first();    // SBK

        // 1. Bahasa Indonesia (BIN)
        if ($linaUser) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $linaUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'BIN']
            );
        }
        if ($taufikUser) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $taufikUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'BIN']
            );
        }

        // 2. Matematika (MTK)
        if ($heryantoUser) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $heryantoUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'MTK']
            );
        }
        if ($budiUser) {
            GuruPengampu::updateOrCreate(
                ['guru_id' => $budiUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'MTK']
            );
        }

        // 3. IPA
        if ($amranUser) {
            GuruPengampu::updateOrCreate(['guru_id' => $amranUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'IPA']);
            GuruPengampu::updateOrCreate(['guru_id' => $amranUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'IPA']);
        }

        // 4. IPS
        if ($sitiUser) {
            GuruPengampu::updateOrCreate(['guru_id' => $sitiUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'IPS']);
            GuruPengampu::updateOrCreate(['guru_id' => $sitiUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'IPS']);
        }

        // 5. Bahasa Inggris (BING)
        if ($johnUser) {
            GuruPengampu::updateOrCreate(['guru_id' => $johnUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'BING']);
            GuruPengampu::updateOrCreate(['guru_id' => $johnUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'BING']);
        }

        // 6. PKN
        if ($pujiUser) {
            GuruPengampu::updateOrCreate(['guru_id' => $pujiUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'PKN']);
            GuruPengampu::updateOrCreate(['guru_id' => $pujiUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'PKN']);
        }

        // 7. PAI
        if ($hasanUser) {
            GuruPengampu::updateOrCreate(['guru_id' => $hasanUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'PAI']);
            GuruPengampu::updateOrCreate(['guru_id' => $hasanUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'PAI']);
        }

        // 8. PJOK
        if ($wawanUser) {
            GuruPengampu::updateOrCreate(['guru_id' => $wawanUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'PJOK']);
            GuruPengampu::updateOrCreate(['guru_id' => $wawanUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'PJOK']);
        }

        // 9. SBK
        if ($endahUser) {
            GuruPengampu::updateOrCreate(['guru_id' => $endahUser->id, 'kelas_id' => $kelas6A->id, 'mapel_id' => 'SBK']);
            GuruPengampu::updateOrCreate(['guru_id' => $endahUser->id, 'kelas_id' => $kelas6B->id, 'mapel_id' => 'SBK']);
        }
    }
}
