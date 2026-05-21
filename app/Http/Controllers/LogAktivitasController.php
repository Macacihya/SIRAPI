<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAktivitasController extends Controller
{
    public function index()
    {
        // Tampilkan log aktivitas terurut terbaru
        $logs = LogAktivitas::with('user')->orderBy('waktu', 'desc')->paginate(20);
        return view('pages.log-aktivitas.index', compact('logs'));
    }

    /**
     * Helper untuk log aktivitas dari controller lain.
     */
    public static function log($judul, $deskripsi = null, $tipeIcon = 'info', $userId = null)
    {
        LogAktivitas::create([
            'user_id'   => $userId ?? Auth::id(),
            'judul'     => $judul,
            'deskripsi' => $deskripsi,
            'waktu'     => now(),
            'tipe_icon' => $tipeIcon,
        ]);
    }
}
