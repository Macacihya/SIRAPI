<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Role;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil tahun ajaran aktif, atau buat satu jika belum ada
        $tahunAjaran = TahunAjaran::firstOrCreate(
            ['tahun_mulai' => 2025, 'tahun_selesai' => 2026, 'semester' => 'Genap'],
            ['is_active' => true]
        );

        $kelasData = [
            '1-A', '1-B',
            '2-A', '2-B',
            '3-A', '3-B',
            '4-A', '4-B',
            '5-A', '5-B',
            '6-A', '6-B',
        ];
        $waliKelas = Guru::query()->orderBy('user_id')->pluck('user_id')->values();

        foreach ($kelasData as $index => $nama) {
            Kelas::updateOrCreate(
                ['nama_kelas' => $nama, 'tahun_ajaran_id' => $tahunAjaran->id],
                [
                    'tingkat' => strtok($nama, '-'),
                    'wali_guru_id' => $waliKelas->isNotEmpty()
                        ? $waliKelas[$index % $waliKelas->count()]
                        : null,
                ]
            );
        }

        // Guru yang dipasang sebagai wali kelas harus punya role pivot walikelas juga.
        // Login mengecek user_roles, sedangkan tabel kelas hanya menyimpan penugasan wali.
        $roleIds = Role::whereIn('nama_role', ['guru', 'walikelas'])->pluck('id')->toArray();
        Guru::whereIn('user_id', $waliKelas)
            ->get()
            ->each(fn (Guru $guru) => $guru->user?->roles()->syncWithoutDetaching($roleIds));
    }
}
