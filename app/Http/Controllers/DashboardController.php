<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = request()->user();

        // Statistik untuk dashboard
        $totalSiswa   = Siswa::count();
        $totalGuru    = Guru::count();
        $totalKelas   = Kelas::count();
        $totalMapel   = MataPelajaran::count();
        $tahunAktif   = TahunAjaran::where('is_active', true)->first();

        return view('pages.dashboard.index', compact(
            'user',
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'totalMapel',
            'tahunAktif'
        ));
    }
}
