<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── SEED SEKOLAH DEFAULT ───────────────────
        $sekolah = Sekolah::updateOrCreate(
            ['npsn' => '20304857'],
            [
                'nama_sekolah'        => 'SD Negeri 01 Indonesia',
                'alamat'              => 'Jl. Teknologi Cerdas No. 45, Kebayoran Baru, Jakarta Selatan',
                'kode_pos'            => '12110',
                'telepon'             => '(021) 7654321',
                'email'               => 'info@smk-tu.sch.id',
                'nip_kepsek'          => '197508212003121002',
                'status_sekolah'      => 'Negeri',
                'nama_kepala_sekolah' => 'Dr. Budi Santoso, M.Pd.',
                'bentuk_pendidikan'   => 'SD (Sekolah Dasar)',
            ]
        );

        // ─── ADMIN ─────────────────────────────────
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama'     => 'Administrator',
                'username' => 'admin',
                'email'    => 'admin@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role'     => 'admin',
            ]
        );

        // ISA child: admins
        Admin::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'jabatan_admin' => 'Admin TU',
            ]
        );

        // ─── GURU ──────────────────────────────────
        $guru = User::updateOrCreate(
            ['username' => 'taufik'],
            [
                'nama'     => 'Drs. M. Taufik',
                'username' => 'taufik',
                'email'    => 'taufik@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role'     => 'guru',
            ]
        );

        // ISA child: gurus
        Guru::updateOrCreate(
            ['user_id' => $guru->id],
            [
                'nip'        => '197805122005011004',
                'sekolah_id' => $sekolah->id,
                'jabatan'    => 'Guru Mata Pelajaran',
            ]
        );

        // ─── WALIKELAS ─────────────────────────────
        $walikelas = User::updateOrCreate(
            ['username' => 'heryanto'],
            [
                'nama'     => 'Heryanto Pratama, S.Pd.',
                'username' => 'heryanto',
                'email'    => 'heryanto@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role'     => 'walikelas',
            ]
        );

        // ISA child: gurus (walikelas juga punya NIP)
        Guru::updateOrCreate(
            ['user_id' => $walikelas->id],
            [
                'nip'        => '198804122015031002',
                'sekolah_id' => $sekolah->id,
                'jabatan'    => 'Wali Kelas 6-A',
            ]
        );
    }
}
