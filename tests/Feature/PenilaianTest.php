<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class PenilaianTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bisa_mengakses_halaman_capaian_kompetensi()
    {
        $guru = User::factory()->create([
             'role' => 'guru'
        ]);

        $response = $this->actingAs($guru)->get('/capaian-kompetensi');

        $response->assertStatus(200);
    }

    public function test_admin_tidak_bisa_mengakses_halaman_capaian_kompetensi()
    {
        $admin = User::factory()->create([
             'role' => 'admin'
        ]);

        $response = $this->actingAs($admin)->get('/capaian-kompetensi');

        // Middleware role biasanya return 403 Forbidden atau redirect.
        // Asumsikan abort(403) di middleware
        $response->assertStatus(403);
    }

    public function test_walikelas_bisa_mengakses_halaman_rapor()
    {
        $walikelas = User::factory()->create([
             'role' => 'walikelas'
        ]);

        $response = $this->actingAs($walikelas)->get('/rapor');

        $response->assertStatus(200);
    }

    public function test_guru_tidak_bisa_mengakses_halaman_rapor()
    {
        $guru = User::factory()->create([
             'role' => 'guru'
        ]);

        $response = $this->actingAs($guru)->get('/rapor');

        $response->assertStatus(403);
    }
}
