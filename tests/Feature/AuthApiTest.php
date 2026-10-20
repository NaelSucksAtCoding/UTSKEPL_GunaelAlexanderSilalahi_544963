<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_berhasil_mengembalikan_token(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message', 'token', 'token_type', 'user']);
    }

    public function test_login_dengan_password_salah_mengembalikan_422(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'salah',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_logout_menghapus_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJson(['message' => 'Logout berhasil.']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_endpoint_tanpa_token_mengembalikan_401(): void
    {
        $this->getJson('/api/me')->assertStatus(401);
        $this->postJson('/api/logout')->assertStatus(401);
    }
}
