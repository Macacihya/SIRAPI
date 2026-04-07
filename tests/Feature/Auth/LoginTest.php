<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.post'), [
            'username' => 'admin',
            'password' => 'secret123',
            'role' => 'admin',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('Selamat datang, Administrator!');
    }

    public function test_user_cannot_login_with_wrong_role(): void
    {
        User::factory()->create([
            'username' => 'guru01',
            'password' => Hash::make('secret123'),
            'role' => 'guru',
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'username' => 'guru01',
            'password' => 'secret123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_when_opening_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));
    }
}
