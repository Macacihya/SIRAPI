<?php

namespace App\Http\Controllers;

use App\Models\Raport;
use App\Models\RekapKehadiran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RekapKehadiranController extends Controller
{
    public function index()
    {
        $rekapKehadirans = RekapKehadiran::with('raport.siswa.kelas')->latest()->get();
        $raports = Raport::with(['siswa', 'tahunAjaran'])->latest()->get();

        return view('pages.kehadiran.index', compact('rekapKehadirans', 'raports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id|unique:rekap_kehadirans,raport_id',
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpha' => 'required|integer|min:0',
        ]);

        RekapKehadiran::create($validated);

        return redirect()
            ->route('kehadiran')
            ->with('success', 'Rekap kehadiran berhasil ditambahkan.');
    }

    public function update(Request $request, RekapKehadiran $rekapKehadiran)
    {
        $validated = $request->validate([
            'raport_id' => [
                'required',
                'exists:raports,id',
                Rule::unique('rekap_kehadirans', 'raport_id')->ignore($rekapKehadiran->id),
            ],
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpha' => 'required|integer|min:0',
        ]);

        $rekapKehadiran->update($validated);

        return redirect()
            ->route('kehadiran')
            ->with('success', 'Rekap kehadiran berhasil diperbarui.');
    }

    public function destroy(RekapKehadiran $rekapKehadiran)
    {
        $rekapKehadiran->delete();

        return redirect()
            ->route('kehadiran')
            ->with('success', 'Rekap kehadiran berhasil dihapus.');
    }
}
