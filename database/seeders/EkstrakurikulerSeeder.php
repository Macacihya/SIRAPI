<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ekstrakurikuler;

class EkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        $eskuls = ['Badminton', 'Futsal', 'Basket', 'Paskibra', 'Pramuka'];
        foreach ($eskuls as $eskul) {
            Ekstrakurikuler::updateOrCreate(
                ['nama_eskul' => $eskul],
                []
            );
        }
    }
}