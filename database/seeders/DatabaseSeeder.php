<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            RiwayatStatusSiswaSeeder::class,
            MataPelajaranSeeder::class,
            AturanPenilaianSeeder::class,
            GuruPengampuSeeder::class,
            RaportSeeder::class,
            SikapSeeder::class,
            NilaiSeeder::class,
            CapaianKompetensiSeeder::class,
            RekapKehadiranSeeder::class,
            NilaiSikapSeeder::class,
            EkstrakurikulerSeeder::class,
            RaportEkskulSeeder::class,
        ]);
    }
}
