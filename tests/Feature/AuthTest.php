<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_user_can_register(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                'authorization' => ['token', 'type'],
            ]);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                'authorization' => ['token', 'type'],
            ]);
    }

    public function test_user_can_refresh_token(): void
    {
        $user = User::factory()->create();

        $token = auth()->login($user);


        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/refresh');


        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                'authorization' => ['token', 'type'],
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertExactJson([
                'message' => 'Successfully logged out',
            ]);
    }

    public function test_register_fails_missing_fields()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Fulano',
        ]);

        $response->assertStatus(422);
    }

    public function test_logout_fails_unauthenticated()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    public function test_refresh_fails_missing_token()
    {
        $response = $this->postJson('/api/refresh');

        $response->assertStatus(401);
    }

    public function test_login_fails_incorrect_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'email_inexistente@example.com',
            'password' => 'senha_incorreta',
        ]);

        $response->assertStatus(401);
    }
}
