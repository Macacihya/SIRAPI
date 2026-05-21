<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kelas6A = Kelas::where('nama_kelas', '6-A')->first();
        $kelas6B = Kelas::where('nama_kelas', '6-B')->first();

        $siswas = [
            ['nisn' => '0051234001', 'nama_siswa' => 'Ahmad Fauzi',        'kelas_id' => $kelas6A?->id],
            ['nisn' => '0051234002', 'nama_siswa' => 'Siti Aisyah',        'kelas_id' => $kelas6A?->id],
            ['nisn' => '0051234003', 'nama_siswa' => 'Budi Santoso',       'kelas_id' => $kelas6A?->id],
            ['nisn' => '0051234004', 'nama_siswa' => 'Dewi Lestari',       'kelas_id' => $kelas6A?->id],
            ['nisn' => '0051234005', 'nama_siswa' => 'Rizky Pratama',      'kelas_id' => $kelas6A?->id],
            ['nisn' => '0051234006', 'nama_siswa' => 'Putri Handayani',    'kelas_id' => $kelas6B?->id],
            ['nisn' => '0051234007', 'nama_siswa' => 'Dimas Arya',         'kelas_id' => $kelas6B?->id],
            ['nisn' => '0051234008', 'nama_siswa' => 'Nadia Rahmawati',    'kelas_id' => $kelas6B?->id],
            ['nisn' => '0051234009', 'nama_siswa' => 'Fajar Nugroho',      'kelas_id' => $kelas6B?->id],
            ['nisn' => '0051234010', 'nama_siswa' => 'Anisa Putri',        'kelas_id' => $kelas6B?->id],
        ];

        foreach ($siswas as $siswa) {
            Siswa::updateOrCreate(
                ['nisn' => $siswa['nisn']],
                $siswa
            );
        }
    }
}
