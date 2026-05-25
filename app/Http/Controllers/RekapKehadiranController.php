<?php

namespace App\Http\Controllers;

use App\Models\RekapKehadiran;
use Illuminate\Http\Request;

class RekapKehadiranController extends Controller
{
    /**
     * Sinkronisasi rekap kehadiran.
     * Mengkonversi UI input (sakit: 2, izin: 0, alpha: 1) 
     * menjadi multiple row di tabel rekap_kehadirans (1 baris per absen).
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id',
            'sakit'     => 'required|integer|min:0',
            'ket_sakit' => 'nullable|string|max:255',
            'izin'      => 'required|integer|min:0',
            'ket_izin'  => 'nullable|string|max:255',
            'alpha'     => 'required|integer|min:0',
            'ket_alpha' => 'nullable|string|max:255',
        ]);

        // Hapus data absen lama agar jumlah terbaru menggantikan rekap sebelumnya.
        RekapKehadiran::where('raport_id', $validated['raport_id'])->delete();

        // Simpan satu baris untuk setiap hari sakit.
        for ($i = 0; $i < $validated['sakit']; $i++) {
            RekapKehadiran::create([
                'raport_id'  => $validated['raport_id'],
                'status'     => 'sakit',
                'keterangan' => $validated['ket_sakit']
            ]);
        }

        // Simpan satu baris untuk setiap hari izin.
        for ($i = 0; $i < $validated['izin']; $i++) {
            RekapKehadiran::create([
                'raport_id'  => $validated['raport_id'],
                'status'     => 'izin',
                'keterangan' => $validated['ket_izin']
            ]);
        }

        // Simpan satu baris untuk setiap hari alpha.
        for ($i = 0; $i < $validated['alpha']; $i++) {
            RekapKehadiran::create([
                'raport_id'  => $validated['raport_id'],
                'status'     => 'alpha',
                'keterangan' => $validated['ket_alpha']
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kehadiran berhasil disimpan.'
            ]);
        }

        return back()->with('success', 'Kehadiran berhasil disimpan.');
    }
}
