<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\RiwayatStatusSiswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

    public function tampilkan(Request $request)
    {
        $query = Siswa::with(['kelas', 'riwayatStatus']);

        // Filter berdasarkan kelas
        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('nama_kelas', $request->kelas);
            });
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10)->withQueryString();

        // Transform agar field sesuai dengan yg dipakai di view Alpine.js
        $data->getCollection()->transform(function ($siswa) {
            $latestStatus = $siswa->riwayatStatus->sortByDesc('id')->first();
            return (object)[
                'id'             => $siswa->id,
                'nama'           => $siswa->nama_siswa,
                'nisn'           => $siswa->nisn,
                'nis'            => $siswa->nisn,
                'kelas'          => $siswa->kelas ? 'Kelas ' . $siswa->kelas->nama_kelas : '-',
                'kelas_id'       => $siswa->kelas_id,
                'jenis_kelamin'  => '-',
                'status'         => strtoupper($latestStatus?->status ?? 'AKTIF'),
                'selected'       => false,
            ];
        });

        // Ambil daftar kelas untuk filter dropdown dan form
        $daftarKelas = Kelas::all()->map(fn($k) => (object)[
            'id'   => $k->id,
            'nama' => 'Kelas ' . $k->nama_kelas,
        ])->toArray();

        // Statistik
        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::whereHas('riwayatStatus', function ($q) {
            $q->where('status', 'Aktif');
        })->count();
        $siswaCuti = Siswa::whereHas('riwayatStatus', function ($q) {
            $q->where('status', 'Cuti');
        })->count();

        return view('pages.siswa.index', compact(
            'data', 'daftarKelas', 'totalSiswa', 'siswaAktif', 'siswaCuti'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn'       => 'required|string|max:20',
            'kelas_id'   => 'nullable|exists:kelas,id',
        ]);

        $siswa = Siswa::create($validated);

        // Buat riwayat status awal
        RiwayatStatusSiswa::create([
            'siswa_id' => $siswa->id,
            'status'   => 'Aktif',
        ]);

        return redirect()
            ->route('siswa')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn'       => 'required|string|max:20',
            'kelas_id'   => 'nullable|exists:kelas,id',
        ]);

        $siswa->update($validated);

        return redirect()
            ->route('siswa')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()
            ->route('siswa')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}