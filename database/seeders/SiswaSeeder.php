<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kelas6A = Kelas::where('nama_kelas', '6-A')->firstOrFail();
        $kelas6B = Kelas::where('nama_kelas', '6-B')->firstOrFail();
        $sekolah = Sekolah::firstOrFail();

        $siswas = [
            [
                'nisn' => '0051234001',
                'nis' => '2526001',
                'nama_siswa' => 'Ahmad Fauzi',
                'tempat_lahir' => 'Jakarta',
                'tgl_lahir' => '2013-01-15',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'kelas_id' => $kelas6A->id,
            ],
            [
                'nisn' => '0051234002',
                'nis' => '2526002',
                'nama_siswa' => 'Siti Aisyah',
                'tempat_lahir' => 'Bogor',
                'tgl_lahir' => '2013-02-21',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Melati No. 12, Bogor',
                'kelas_id' => $kelas6A->id,
            ],
            [
                'nisn' => '0051234003',
                'nis' => '2526003',
                'nama_siswa' => 'Budi Santoso',
                'tempat_lahir' => 'Depok',
                'tgl_lahir' => '2013-03-09',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Kenanga No. 7, Depok',
                'kelas_id' => $kelas6A->id,
            ],
            [
                'nisn' => '0051234004',
                'nis' => '2526004',
                'nama_siswa' => 'Dewi Lestari',
                'tempat_lahir' => 'Bekasi',
                'tgl_lahir' => '2013-04-18',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Anggrek No. 5, Bekasi',
                'kelas_id' => $kelas6A->id,
            ],
            [
                'nisn' => '0051234005',
                'nis' => '2526005',
                'nama_siswa' => 'Rizky Pratama',
                'tempat_lahir' => 'Tangerang',
                'tgl_lahir' => '2013-05-27',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Cendana No. 14, Tangerang',
                'kelas_id' => $kelas6A->id,
            ],
            [
                'nisn' => '0051234006',
                'nis' => '2526006',
                'nama_siswa' => 'Putri Handayani',
                'tempat_lahir' => 'Jakarta',
                'tgl_lahir' => '2013-06-11',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Mawar No. 8, Jakarta',
                'kelas_id' => $kelas6B->id,
            ],
            [
                'nisn' => '0051234007',
                'nis' => '2526007',
                'nama_siswa' => 'Dimas Arya',
                'tempat_lahir' => 'Bogor',
                'tgl_lahir' => '2013-07-24',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Flamboyan No. 3, Bogor',
                'kelas_id' => $kelas6B->id,
            ],
            [
                'nisn' => '0051234008',
                'nis' => '2526008',
                'nama_siswa' => 'Nadia Rahmawati',
                'tempat_lahir' => 'Depok',
                'tgl_lahir' => '2013-08-16',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Dahlia No. 10, Depok',
                'kelas_id' => $kelas6B->id,
            ],
            [
                'nisn' => '0051234009',
                'nis' => '2526009',
                'nama_siswa' => 'Fajar Nugroho',
                'tempat_lahir' => 'Bekasi',
                'tgl_lahir' => '2013-09-05',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Teratai No. 6, Bekasi',
                'kelas_id' => $kelas6B->id,
            ],
            [
                'nisn' => '0051234010',
                'nis' => '2526010',
                'nama_siswa' => 'Anisa Putri',
                'tempat_lahir' => 'Tangerang',
                'tgl_lahir' => '2013-10-30',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Bougenville No. 9, Tangerang',
                'kelas_id' => $kelas6B->id,
            ],
        ];

        foreach ($siswas as $siswa) {
            Siswa::updateOrCreate(
                ['nisn' => $siswa['nisn']],
                [
                    ...$siswa,
                    'status_aktif' => true,
                    'jabatan_kelas' => 'Anggota',
                    'sekolah_id' => $sekolah->id,
                ]
            );
        }
    }
}
