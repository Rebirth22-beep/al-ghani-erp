<?php

namespace Tests\Feature\Accounts;

use App\Models\ChartOfAccount;
use App\Models\User;
use App\Services\JournalVoucherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

/**
 * Critical correctness rule (project-rules-and-decisions.md §19):
 * A JV must balance — SUM(debit) == SUM(credit). Any imbalance must throw.
 */
class JvBalanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_balanced_jv_is_persisted_and_writes_to_unified_journal(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cash    = ChartOfAccount::create(['code' => '1000', 'name' => 'Cash',    'account_type' => 'asset',  'is_system' => true]);
        $capital = ChartOfAccount::create(['code' => '3000', 'name' => 'Capital', 'account_type' => 'equity', 'is_system' => true]);

        $voucher = app(JournalVoucherService::class)->create([
            'date'      => '2026-05-07',
            'narration' => 'Opening cash',
            'lines'     => [
                ['chart_of_account_id' => $cash->id,    'debit_paisas' => 50_000, 'credit_paisas' => 0],
                ['chart_of_account_id' => $capital->id, 'debit_paisas' => 0,      'credit_paisas' => 50_000],
            ],
        ]);

        $this->assertSame(50_000, (int) $voucher->total_paisas);
        $this->assertDatabaseCount('jv_voucher_lines', 2);
        // The unified journal must also have received this voucher (so reports include it).
        $this->assertDatabaseCount('journal_entry_lines', 2);
    }

    public function test_unbalanced_jv_is_rejected_before_any_row_is_written(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cash    = ChartOfAccount::create(['code' => '1000', 'name' => 'Cash',    'account_type' => 'asset',  'is_system' => true]);
        $capital = ChartOfAccount::create(['code' => '3000', 'name' => 'Capital', 'account_type' => 'equity', 'is_system' => true]);

        $this->expectException(RuntimeException::class);

        app(JournalVoucherService::class)->create([
            'date'      => '2026-05-07',
            'narration' => 'Bad JV',
            'lines'     => [
                ['chart_of_account_id' => $cash->id,    'debit_paisas' => 50_000, 'credit_paisas' => 0],
                ['chart_of_account_id' => $capital->id, 'debit_paisas' => 0,      'credit_paisas' => 40_000],
            ],
        ]);

        // Nothing should have been persisted.
        $this->assertDatabaseCount('jv_vouchers', 0);
        $this->assertDatabaseCount('journal_entry_lines', 0);
    }
}
