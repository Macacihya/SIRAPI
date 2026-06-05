<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first()
            ?? TahunAjaran::orderByDesc('tahun_mulai')->first();

        if (!$tahunAjaran) {
            $tahunAjaran = TahunAjaran::create([
                'tahun_mulai'   => 2025,
                'tahun_selesai' => 2026,
                'semester'      => 'Genap',
                'is_active'     => true,
            ]);
        }

        $taufikUser   = User::where('username', 'taufik')->first();
        $heryantoUser = User::where('username', 'heryanto')->first();

        // Hanya 2 kelas yang dibuat — 6-A dan 6-B
        Kelas::updateOrCreate(
            ['nama_kelas' => '6-A', 'tahun_ajaran_id' => $tahunAjaran->id],
            [
                'tingkat'      => '6',
                'wali_guru_id' => $taufikUser?->id,
            ]
        );

        Kelas::updateOrCreate(
            ['nama_kelas' => '6-B', 'tahun_ajaran_id' => $tahunAjaran->id],
            [
                'tingkat'      => '6',
                'wali_guru_id' => $heryantoUser?->id,
            ]
        );

        // Pastikan kedua wali kelas memiliki role yang benar
        $roleIds = Role::whereIn('nama_role', ['guru', 'walikelas'])->pluck('id')->toArray();
        foreach ([$taufikUser, $heryantoUser] as $u) {
            $u?->roles()->syncWithoutDetaching($roleIds);
        }
    }
}
