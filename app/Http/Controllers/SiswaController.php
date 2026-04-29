<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SiswaController extends Controller
{
    public function getData()
    {
        // Generate dummy data
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dani', 'Eka', 'Fitri', 'Galih', 'Hana', 'Iqbal', 'Joko', 'Kartika', 'Lukman', 'Maya', 'Naufal', 'Olivia'];
        $lastNames = ['Fauzi', 'Santoso', 'Dewi', 'Pratama', 'Rahmawati', 'Handayani', 'Saputra', 'Lestari', 'Kurniawan', 'Widodo', 'Sari', 'Hakim', 'Putri', 'Zaki', 'Maharani'];
        $classes = ['X IPA 1', 'X IPA 2', 'X IPS 1', 'XI IPA 1', 'XI IPS 2', 'XII IPA 1', 'XII IPS 1'];
        $genders = ['Laki-laki', 'Perempuan'];

        $dataSiswa = [];
        for ($i = 1; $i <= 55; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            // simplistic gender guess
            $gender = in_array(explode(' ', $name)[0], ['Ahmad', 'Budi', 'Dani', 'Galih', 'Iqbal', 'Joko', 'Lukman', 'Naufal']) ? 'Laki-laki' : 'Perempuan';
            
            $dataSiswa[] = [
                'id' => $i,
                'nama' => $name,
                'nis' => '2024' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kelas' => $classes[array_rand($classes)],
                'jenis_kelamin' => $gender,
            ];
        }

        return collect($dataSiswa);
    }

    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

    public function tampilkan(Request $request)
    {
        $allData = $this->getData();
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $data = new LengthAwarePaginator($currentPageItems, $allData->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        // route uses 'pages.siswa.index' typically, let's verify if 'pages.siswa' exists or if it's 'pages.siswa.index'
        // the previous code returned view('pages.siswa', compact('data'));
        // let's stick to 'pages.siswa.index' if it's the new standard, or fallback. We'll use 'pages.siswa.index' assuming it's refactored.
        // Wait, routes/web.php has: Route::view('/siswa', 'pages.siswa.index')->name('siswa'); and get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])
        return view('pages.siswa.index', compact('data'));
    }
}