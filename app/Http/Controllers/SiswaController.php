<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function getData()
    {
        $dataSiswa = [
            ['id' => 1, 'nama' => 'Ahmad Fauzi',   'nis' => '2024001', 'kelas' => 'X IPA 1'],
            ['id' => 2, 'nama' => 'Budi Santoso',  'nis' => '2024002', 'kelas' => 'X IPA 2'],
            ['id' => 3, 'nama' => 'Citra Dewi',    'nis' => '2024003', 'kelas' => 'XI IPS 1'],
            ['id' => 4, 'nama' => 'Dani Pratama',  'nis' => '2024004', 'kelas' => 'XI IPS 2'],
            ['id' => 5, 'nama' => 'Eka Rahmawati', 'nis' => '2024005', 'kelas' => 'XII IPA 1'],
        ];

        return $dataSiswa;
    }

    public function tampilkan()
    {
        $data = $this->getData();
        return view('pages.siswa', compact('data'));
    }
}