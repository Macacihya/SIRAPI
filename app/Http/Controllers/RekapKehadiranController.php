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

        // Hapus data absen lama untuk raport ini
        RekapKehadiran::where('raport_id', $validated['raport_id'])->delete();

        // Insert row sesuai jumlah sakit
        for ($i = 0; $i < $validated['sakit']; $i++) {
            RekapKehadiran::create([
                'raport_id'  => $validated['raport_id'],
                'status'     => 'sakit',
                'keterangan' => $validated['ket_sakit']
            ]);
        }

        // Insert row sesuai jumlah izin
        for ($i = 0; $i < $validated['izin']; $i++) {
            RekapKehadiran::create([
                'raport_id'  => $validated['raport_id'],
                'status'     => 'izin',
                'keterangan' => $validated['ket_izin']
            ]);
        }

        // Insert row sesuai jumlah alpha
        for ($i = 0; $i < $validated['alpha']; $i++) {
            RekapKehadiran::create([
                'raport_id'  => $validated['raport_id'],
                'status'     => 'alpha',
                'keterangan' => $validated['ket_alpha']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kehadiran berhasil disimpan.'
        ]);
    }
}

