<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ekstrakurikuler;

class EkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        Ekstrakurikuler::insert([
            [
                'nama_eskul' => 'Badminton',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_eskul' => 'Futsal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_eskul' => 'Basket',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_eskul' => 'Paskibra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_eskul' => 'Pramuka',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}