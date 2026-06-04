<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CapaianKompetensi;
use App\Models\Nilai;

class CapaianKompetensiSeeder extends Seeder
{
    public function run(): void
    {
        $nilai = Nilai::firstOrFail();

        CapaianKompetensi::updateOrCreate(
            ['nilai_id' => $nilai->id],
            [
                'deskripsi' => 'Mampu memahami gagasan utama bacaan, menyampaikan pendapat dengan runtut, dan menggunakan kosakata yang tepat.',
            ]
        );
    }
}
