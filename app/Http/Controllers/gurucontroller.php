<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Sekolah;

class GuruController extends Controller
{
    private function getMockGurus()
    {
        $firstNames = ['Budi', 'Siti', 'Ahmad', 'Dewi', 'Rudi', 'Hendra', 'Rina', 'Agus', 'Ratna', 'Cipto', 'Bambang', 'Endang'];
        $lastNames = ['Santoso', 'Rahayu', 'Fauzi', 'Lestari', 'Hermawan', 'Gunawan', 'Sari', 'Salim', 'Kusuma', 'Wijaya'];
        $subjects = ['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'PJOK', 'Bahasa Inggris', 'Seni Budaya', 'Prakarya', '-'];

        $gurus = [];
        for ($i = 1; $i <= 35; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            $email = strtolower(str_replace(' ', '.', $name)) . '@school.id';
            $roles = (rand(1, 10) > 7) ? ['WALI KELAS XI IPA 1'] : ['GURU MAPEL'];
            $mapel = in_array('GURU MAPEL', $roles) ? $subjects[array_rand($subjects)] : '-';

            $gurus[] = (object)[
                'name' => $name,
                'email' => $email,
                'nip' => '19' . rand(70, 95) . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT) . '20' . rand(10, 23) . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '100' . rand(1, 9),
                'roles' => $roles,
                'mapel' => $mapel
            ];
        }
        return collect($gurus);
    }

    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

    public function tampilkan(Request $request)
    {
        $allData = $this->getMockGurus();
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $gurus = new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems, $allData->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('pages.guru-tendik.index', compact('gurus')); // Updated view path based on route Route::view('/guru-tendik', 'pages.guru-tendik.index')
    }

    public function create()
    {
        $sekolahs = Sekolah::all();
        return view('pages.guru.create', compact('sekolahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'mata_pelajaran' => 'required',
            'sekolah_id' => 'required'
        ]);

        Guru::create($request->all());

        return redirect()->route('guru.index');
    }
}
