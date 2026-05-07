<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email'    => 'admin@alghani.com',
            'password' => Hash::make('Admin@123'),
            'role'     => UserRole::Admin,
            'is_active'=> true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@alghani.com',
            'password' => 'Admin@123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['token', 'user' => ['id', 'name', 'email', 'role']],
            ])
            ->assertJsonPath('success', true);
    }

    public function test_invalid_password_returns_validation_error(): void
    {
        User::factory()->create([
            'email'    => 'admin@alghani.com',
            'password' => Hash::make('Admin@123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@alghani.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
