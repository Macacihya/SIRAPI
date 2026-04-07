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
                'email' => 'admin@sirapi.com',
                'password' => Hash::make('lopolo9090'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['username' => 'guru01'],
            [
                'name' => 'Guru Satu',
                'username' => 'guru01',
                'email' => 'guru01@sirapi.com',
                'password' => Hash::make('lopolo9090'),
                'role' => 'guru',
            ]
        );

        User::updateOrCreate(
            ['username' => 'walikelas01'],
            [
                'name' => 'Wali Kelas Satu',
                'username' => 'walikelas01',
                'email' => 'walikelas01@sirapi.com',
                'password' => Hash::make('lopolo9090'),
                'role' => 'walikelas',
            ]
        );
    }
}
