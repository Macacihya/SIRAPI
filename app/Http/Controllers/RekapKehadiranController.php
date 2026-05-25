<?php

namespace App\Http\Controllers;

use App\Models\RekapKehadiran;
use Illuminate\Http\Request;

class RekapKehadiranController extends Controller
{
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id',
            'sakit'     => 'required|integer|min:0',
            'izin'      => 'required|integer|min:0',
            'alpha'     => 'required|integer|min:0',
        ]);

        // Simpan total kehadiran dalam satu baris rekap untuk setiap rapor.
        RekapKehadiran::updateOrCreate(
            ['raport_id' => $validated['raport_id']],
            [
                'sakit' => $validated['sakit'],
                'izin' => $validated['izin'],
                'alpha' => $validated['alpha'],
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kehadiran berhasil disimpan.'
            ]);
        }

        return back()->with('success', 'Kehadiran berhasil disimpan.');
    }
}
