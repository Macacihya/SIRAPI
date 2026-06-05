<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class ProfilKelasController extends Controller
{
    public function index()
    {
        $userId     = auth()->id();
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // Ambil kelas yang menjadi binaan wali kelas yang sedang login.
        $kelasList = Kelas::with(['waliGuru.user', 'tahunAjaran'])
            ->where('wali_guru_id', $userId)
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('nama_kelas')
            ->get();

        // Ambil kelas pertama sebagai default tampilan (wali biasanya hanya punya 1 kelas).
        $kelas = $kelasList->first();

        // Ambil data siswa aktif di kelas tersebut beserta relasi yang dibutuhkan.
        $siswas = collect();
        if ($kelas) {
            $siswas = Siswa::where('kelas_id', $kelas->id)
                ->where('status_aktif', true)
                ->orderBy('nama_siswa')
                ->get()
                ->map(function ($siswa, $index) {
                    $namaParts = collect(explode(' ', trim($siswa->nama_siswa)))->filter();
                    $initials  = $namaParts->take(2)->map(fn ($w) => strtoupper(substr($w, 0, 1)))->implode('');

                    return [
                        'no'    => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                        'nis'   => ($siswa->nis ?? '-') . ' / ' . ($siswa->nisn ?? '-'),
                        'init'  => $initials ?: '??',
                        'nama'  => strtoupper($siswa->nama_siswa),
                        'jk'    => in_array($siswa->jenis_kelamin, ['L', 'Pria'])   ? 'Laki-laki' :
                                   (in_array($siswa->jenis_kelamin, ['P', 'Wanita']) ? 'Perempuan' : '-'),
                        'role'  => 'SISWA',
                    ];
                });
        }

        // Nama wali kelas dari relasi.
        $namaWali  = $kelas?->waliGuru?->user?->nama ?? auth()->user()?->nama ?? '-';
        $mapelWali = $kelas?->waliGuru?->mata_pelajaran ?? '-';

        return view('pages.profil-kelas.index', compact(
            'kelas',
            'kelasList',
            'siswas',
            'namaWali',
            'mapelWali',
            'tahunAktif',
        ));
    }
}
