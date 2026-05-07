<?php

namespace Tests\Feature\Settings;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SettingAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_settings(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/v1/settings')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_salesman_cannot_view_settings(): void
    {
        $salesman = User::factory()->create(['role' => UserRole::Salesman]);

        Sanctum::actingAs($salesman);

        $this->getJson('/api/v1/settings')
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }

    public function test_accountant_cannot_update_settings(): void
    {
        $accountant = User::factory()->create(['role' => UserRole::Accountant]);

        Sanctum::actingAs($accountant);

        $this->putJson('/api/v1/settings', [
                'settings' => [
                    'company_name' => 'Al-Ghani',
                ],
            ])
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }
}
