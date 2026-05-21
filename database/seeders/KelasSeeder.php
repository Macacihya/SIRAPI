<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil tahun ajaran aktif, atau buat satu jika belum ada
        $tahunAjaran = TahunAjaran::firstOrCreate(
            ['tahun_mulai' => 2025, 'tahun_selesai' => 2026, 'semester' => 'Genap'],
            ['is_active' => true]
        );

        $kelasData = [
            '1-A', '1-B',
            '2-A', '2-B',
            '3-A', '3-B',
            '4-A', '4-B',
            '5-A', '5-B',
            '6-A', '6-B',
        ];

        foreach ($kelasData as $nama) {
            Kelas::updateOrCreate(
                ['nama_kelas' => $nama, 'tahun_ajaran_id' => $tahunAjaran->id],
            );
        }
    }
}
