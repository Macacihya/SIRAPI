<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekapKehadiran;

class RekapKehadiranSeeder extends Seeder
{
    public function run(): void
    {
        // 2 hari sakit
        RekapKehadiran::create(['raport_id' => 1, 'status' => 'sakit', 'keterangan' => 'Demam Berdarah']);
        RekapKehadiran::create(['raport_id' => 1, 'status' => 'sakit', 'keterangan' => 'Masih tahap pemulihan']);
        
        // 1 hari izin
        RekapKehadiran::create(['raport_id' => 1, 'status' => 'izin', 'keterangan' => 'Acara keluarga']);
    }
}