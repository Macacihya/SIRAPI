<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CapaianKompetensi;

class CapaianKompetensiSeeder extends Seeder
{
    public function run(): void
    {
        CapaianKompetensi::create([
            'nilai_id' => 1,
        ]);
    }
}