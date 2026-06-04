<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\Role;
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
                'jenis_kelamin' => 'Pria',
                'no_hp'         => '081234560001',
                'alamat'        => 'Jl. Teknologi Cerdas No. 45, Kebayoran Baru, Jakarta Selatan',
            ]
        );
        DB::table('users')->where('id', $admin->id)->update([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $admin->roles()->sync(Role::where('nama_role', 'admin')->pluck('id')->toArray());

        Admin::updateOrCreate(
            ['user_id' => $admin->id],
            []
        );

        // ─── DAFTAR GURU & MAPEL ─────────────────────
        // Password default semua akun adalah: lopolo9090
        $guruData = [
            [
                'username' => 'taufik',
                'nama' => 'Drs. M. Taufik',
                'email' => 'taufik@sekolah.sch.id',
                'jenis_kelamin' => 'Pria',
                'nip' => '197805122005011004',
                'jabatan' => 'Wali Kelas 6-A / Guru B. Indonesia (Kelas 6-B)',
                'role_type' => 'walikelas',
            ],
            [
                'username' => 'lina',
                'nama' => 'Lina Marlina, S.Pd.',
                'email' => 'lina@sekolah.sch.id',
                'jenis_kelamin' => 'Wanita',
                'nip' => '198503122010032002',
                'jabatan' => 'Guru B. Indonesia (Kelas 6-A)',
                'role_type' => 'guru',
            ],
            [
                'username' => 'heryanto',
                'nama' => 'Heryanto Pratama, S.Pd.',
                'email' => 'heryanto@sekolah.sch.id',
                'jenis_kelamin' => 'Pria',
                'nip' => '198804122015031002',
                'jabatan' => 'Wali Kelas 6-B / Guru Matematika (Kelas 6-A)',
                'role_type' => 'walikelas',
            ],
            [
                'username' => 'budi',
                'nama' => 'Budi Setiawan, S.Pd.',
                'email' => 'budi@sekolah.sch.id',
                'jenis_kelamin' => 'Pria',
                'nip' => '198609202012031003',
                'jabatan' => 'Guru Matematika (Kelas 6-B)',
                'role_type' => 'guru',
            ],
            [
                'username' => 'amran',
                'nama' => 'Drs. Amran Hakim',
                'email' => 'amran@sekolah.sch.id',
                'jenis_kelamin' => 'Pria',
                'nip' => '197509152003121003',
                'jabatan' => 'Guru IPA',
                'role_type' => 'guru',
            ],
            [
                'username' => 'siti',
                'nama' => 'Siti Rahmawati, S.Pd.',
                'email' => 'siti@sekolah.sch.id',
                'jenis_kelamin' => 'Wanita',
                'nip' => '198407222010012005',
                'jabatan' => 'Guru IPS',
                'role_type' => 'guru',
            ],
            [
                'username' => 'john',
                'nama' => 'John Doe, M.Pd.',
                'email' => 'john@sekolah.sch.id',
                'jenis_kelamin' => 'Pria',
                'nip' => '198911052018031001',
                'jabatan' => 'Guru Bahasa Inggris',
                'role_type' => 'guru',
            ],
            [
                'username' => 'puji',
                'nama' => 'Puji Astuti, S.Pd.',
                'email' => 'puji@sekolah.sch.id',
                'jenis_kelamin' => 'Wanita',
                'nip' => '198205182008012002',
                'jabatan' => 'Guru PKN',
                'role_type' => 'guru',
            ],
            [
                'username' => 'hasan',
                'nama' => 'Hasan Basri, S.Ag.',
                'email' => 'hasan@sekolah.sch.id',
                'jenis_kelamin' => 'Pria',
                'nip' => '197903102006041002',
                'jabatan' => 'Guru Pendidikan Agama Islam',
                'role_type' => 'guru',
            ],
            [
                'username' => 'wawan',
                'nama' => 'Wawan Hermawan, S.Pd.',
                'email' => 'wawan@sekolah.sch.id',
                'jenis_kelamin' => 'Pria',
                'nip' => '199108142020121003',
                'jabatan' => 'Guru PJOK',
                'role_type' => 'guru',
            ],
            [
                'username' => 'endah',
                'nama' => 'Endah Lestari, S.Sn.',
                'email' => 'endah@sekolah.sch.id',
                'jenis_kelamin' => 'Wanita',
                'nip' => '198706302014032001',
                'jabatan' => 'Guru SBK',
                'role_type' => 'guru',
            ],
        ];

        foreach ($guruData as $data) {
            $user = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'nama'          => $data['nama'],
                    'username'      => $data['username'],
                    'email'         => $data['email'],
                    'password'      => Hash::make('lopolo9090'),
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'no_hp'         => '08123456' . substr($data['nip'], -4),
                    'alamat'        => 'Jl. Pendidikan No. ' . rand(1, 100) . ', Jakarta Selatan',
                ]
            );

            DB::table('users')->where('id', $user->id)->update([
                'role' => $data['role_type'],
                'email_verified_at' => now(),
            ]);

            $roles = $data['role_type'] === 'walikelas' 
                ? Role::whereIn('nama_role', ['guru', 'walikelas'])->pluck('id')->toArray()
                : Role::where('nama_role', 'guru')->pluck('id')->toArray();
                
            $user->roles()->sync($roles);

            Guru::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip'        => $data['nip'],
                    'sekolah_id' => $sekolah->id,
                    'jabatan'    => $data['jabatan'],
                ]
            );
        }
    }
}
