<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['role' => 'guru'],
            [
                'name' => 'Drs. M. Taufik',
                'username' => 'taufik',
                'nip' => '197805122005011004',
                'email' => 'taufik@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role' => 'guru',
            ]
        );

        User::updateOrCreate(
            ['role' => 'walikelas'],
            [
                'name' => 'Heryanto Pratama, S.Pd.',
                'username' => 'heryanto',
                'nip' => '198804122015031002',
                'email' => 'heryanto@sekolah.sch.id',
                'password' => Hash::make('lopolo9090'),
                'role' => 'walikelas',
            ]
        );
    }
}
