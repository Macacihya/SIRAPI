<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_tampil_dengan_benar(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sistem Informasi');
    }

    public function test_admin_bisa_login_dan_masuk_dashboard(): void
    {
        $admin = User::factory()->create([
            'username' => 'admindemo',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'username' => 'admindemo',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/dashboard');
    }

    public function test_guru_bisa_login_menggunakan_email(): void
    {
        $guru = User::factory()->create([
            'email' => 'guru@sekolah.com',
            'password' => bcrypt('password123'),
            'role' => 'guru',
        ]);

        $response = $this->post('/login', [
            'username' => 'guru@sekolah.com', // Login request menggunakan key 'username'
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $this->assertAuthenticatedAs($guru);
        $response->assertRedirect('/dashboard');
    }

    public function test_walikelas_bisa_login_menggunakan_nip(): void
    {
        $walikelas = User::factory()->create([
            'nip' => '198001012010011001',
            'password' => bcrypt('password123'),
            'role' => 'walikelas',
        ]);

        $response = $this->post('/login', [
            'username' => '198001012010011001',
            'password' => 'password123',
            'role' => 'walikelas',
        ]);

        $this->assertAuthenticatedAs($walikelas);
        $response->assertRedirect('/dashboard');
    }

    public function test_login_gagal_jika_role_tidak_sesuai(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'role' => 'guru',
        ]);

        // Uji login admin tapi memakai akun guru
        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->assertGuest();
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }

    public function test_login_gagal_jika_sandi_salah(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password-salah',
            'role' => 'admin'
        ]);

        $this->assertGuest();
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }
}
