<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Sekolah;
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
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
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
                'nis'            => $siswa->nis,
                'kelas'          => $siswa->kelas ? 'Kelas ' . $siswa->kelas->nama_kelas : '-',
                'kelas_id'       => $siswa->kelas_id,
                'jenis_kelamin'  => $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
                'jk_raw'         => $siswa->jenis_kelamin,
                'tempat_lahir'   => $siswa->tempat_lahir ?? '-',
                'tgl_lahir'      => $siswa->tgl_lahir ? $siswa->tgl_lahir->format('Y-m-d') : '',
                'alamat'         => $siswa->alamat ?? '',
                'status'         => strtoupper($latestStatus?->status ?? ($siswa->status_aktif ? 'AKTIF' : 'NONAKTIF')),
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
        $siswaAktif = Siswa::where('status_aktif', true)->count();
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
            'nama_siswa'    => 'required|string|max:255',
            'nisn'          => 'required|string|max:20|unique:siswas,nisn',
            'nis'           => 'nullable|string|max:20|unique:siswas,nis',
            'kelas_id'      => 'nullable|exists:kelas,id',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tgl_lahir'     => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string',
        ]);

        // Otomatis set sekolah_id ke sekolah pertama
        $sekolah = Sekolah::first();
        $validated['sekolah_id'] = $sekolah?->id;
        $validated['status_aktif'] = true;

        $siswa = Siswa::create($validated);

        // Buat riwayat status awal
        RiwayatStatusSiswa::create([
            'siswa_id'           => $siswa->id,
            'status'             => 'Aktif',
            'keterangan'         => 'Pendaftaran siswa baru',
            'tanggal_perubahan'  => now()->toDateString(),
        ]);

        return redirect()
            ->route('siswa')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama_siswa'    => 'required|string|max:255',
            'nisn'          => 'required|string|max:20|unique:siswas,nisn,' . $siswa->id,
            'nis'           => 'nullable|string|max:20|unique:siswas,nis,' . $siswa->id,
            'kelas_id'      => 'nullable|exists:kelas,id',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tgl_lahir'     => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string',
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