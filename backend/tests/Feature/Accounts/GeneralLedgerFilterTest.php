<?php

namespace Tests\Feature\Accounts;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GeneralLedgerFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_null_per_page_uses_default_page_size(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        Sanctum::actingAs($admin);

        $this->json('GET', '/api/v1/accounts/general-ledger', [
            'per_page' => null,
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.current_page', 1);
    }
}
