<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── ADMIN ─────────────────────────────────
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role' => 'admin',
                'jabatan' => 'Admin TU',
                'jenis_kelamin' => 'Laki-laki',
                'no_hp' => '+62 812-3456-7890',
                'alamat' => 'Jl. Pendidikan No. 1, Jakarta',
            ]
        );

        // ISA child: admins
        Admin::updateOrCreate(
            ['user_id' => $admin->id],
            ['jabatan_admin' => 'Tata Usaha']
        );

        // ─── GURU ──────────────────────────────────
        $guru = User::updateOrCreate(
            ['username' => 'taufik'],
            [
                'nama' => 'Drs. M. Taufik',
                'username' => 'taufik',
                'email' => 'taufik@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role' => 'guru',
                'jabatan' => 'Guru Mata Pelajaran',
                'jenis_kelamin' => 'Laki-laki',
                'no_hp' => '+62 821-1234-5678',
                'alamat' => 'Jl. Merdeka No. 45, Bandung',
            ]
        );

        // ISA child: gurus
        Guru::updateOrCreate(
            ['user_id' => $guru->id],
            [
                'nip' => '197805122005011004',
                'mata_pelajaran' => 'Bahasa Indonesia'
            ]
        );

        // ─── WALIKELAS ─────────────────────────────
        $walikelas = User::updateOrCreate(
            ['username' => 'heryanto'],
            [
                'nama' => 'Heryanto Pratama, S.Pd.',
                'username' => 'heryanto',
                'email' => 'heryanto@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role' => 'walikelas',
                'jabatan' => 'Wali Kelas 6-A',
                'jenis_kelamin' => 'Laki-laki',
                'no_hp' => '+62 852-9876-5432',
                'alamat' => 'Jl. Pahlawan No. 99, Surabaya',
            ]
        );

        // ISA child: gurus (walikelas juga punya NIP)
        Guru::updateOrCreate(
            ['user_id' => $walikelas->id],
            [
                'nip' => '198804122015031002',
                'mata_pelajaran' => 'Matematika'
            ]
        );
    }
}
