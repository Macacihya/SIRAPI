<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Raport;
use App\Models\TahunAjaran;
use App\Models\Siswa;

class RaportSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaranDefault = TahunAjaran::where('is_active', true)->first()
            ?? TahunAjaran::orderByDesc('tahun_mulai')->first();

        $siswas = Siswa::all();

        foreach ($siswas as $siswa) {
            $tahunAjaranId = $siswa->kelas?->tahun_ajaran_id ?? ($tahunAjaranDefault?->id);
            if ($tahunAjaranId) {
                Raport::updateOrCreate([
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $tahunAjaranId,
                ], [
                    'status' => 'draft',
                    'catatan_wali' => 'Tingkatkan prestasi belajar dan rajinlah membaca di rumah.',
                ]);
            }
        }
    }
}