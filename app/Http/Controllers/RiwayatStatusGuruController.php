<?php

namespace App\Http\Controllers;

use App\Models\RiwayatStatusGuru;
use App\Models\Guru;
use Illuminate\Http\Request;

class RiwayatStatusGuruController extends Controller
{
    public function index($guruId)
    {
        $guru = Guru::with('user', 'riwayatStatus')->findOrFail($guruId);
        return view('pages.riwayat-status-guru.index', compact('guru'));
    }

    public function store(Request $request, $guruId)
    {
        $request->validate([
            'status'            => 'required|string|max:50',
            'keterangan'        => 'nullable|string',
            'tanggal_perubahan' => 'required|date',
        ]);

        RiwayatStatusGuru::create([
            'guru_id'           => $guruId,
            'status'            => $request->status,
            'keterangan'        => $request->keterangan,
            'tanggal_perubahan' => $request->tanggal_perubahan,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Riwayat status guru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $riwayat = RiwayatStatusGuru::findOrFail($id);
        $riwayat->delete();

        return redirect()
            ->back()
            ->with('success', 'Riwayat status guru berhasil dihapus.');
    }
}
