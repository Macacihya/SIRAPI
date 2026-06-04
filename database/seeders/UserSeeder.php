<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'nama'          => 'Administrator',
                'username'      => 'admin',
                'email'         => 'admin@sekolah.sch.id',
                'password'      => Hash::make('lopolo9090'),
                'jenis_kelamin' => 'L',
                'no_hp'         => '081234560001',
                'alamat'        => 'Jl. Teknologi Cerdas No. 45, Kebayoran Baru, Jakarta Selatan',
            ]
        );
        DB::table('users')->where('id', $admin->id)->update([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $admin->roles()->sync(\App\Models\Role::where('nama_role', 'admin')->pluck('id')->toArray());

        // ISA child: admins
        Admin::updateOrCreate(
            ['user_id' => $admin->id],
            []
        );

        // ─── GURU ──────────────────────────────────
        $guru = User::updateOrCreate(
            ['username' => 'taufik'],
            [
                'nama'          => 'Drs. M. Taufik',
                'username'      => 'taufik',
                'email'         => 'taufik@sekolah.sch.id',
                'password'      => Hash::make('lopolo9090'),
                'jenis_kelamin' => 'L',
                'no_hp'         => '081234560002',
                'alamat'        => 'Jl. Pendidikan No. 12, Jakarta Selatan',
            ]
        );
        DB::table('users')->where('id', $guru->id)->update([
            'role' => 'guru',
            'email_verified_at' => now(),
        ]);
        $guru->roles()->sync(\App\Models\Role::where('nama_role', 'guru')->pluck('id')->toArray());

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
                'nama'          => 'Heryanto Pratama, S.Pd.',
                'username'      => 'heryanto',
                'email'         => 'heryanto@sekolah.sch.id',
                'password'      => Hash::make('lopolo9090'),
                'jenis_kelamin' => 'L',
                'no_hp'         => '081234560003',
                'alamat'        => 'Jl. Guru Teladan No. 8, Jakarta Selatan',
            ]
        );
        DB::table('users')->where('id', $walikelas->id)->update([
            'role' => 'walikelas',
            'email_verified_at' => now(),
        ]);
        $walikelas->roles()->sync(\App\Models\Role::whereIn('nama_role', ['guru', 'walikelas'])->pluck('id')->toArray());

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
