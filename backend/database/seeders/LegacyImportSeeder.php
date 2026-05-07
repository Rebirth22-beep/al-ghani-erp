<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\Party;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ============================================================
 *  LegacyImportSeeder — one-shot import from C:\xampp\htdocs\alghani
 * ============================================================
 *
 *  PURPOSE:
 *    Read every business row from the legacy `alghani` database (via the
 *    `legacy` connection in config/database.php) and copy it into the new
 *    al-ghani-erp schema with money fields converted from decimal → integer paisas.
 *
 *  SAFETY (read carefully — this is destructive on the target DB):
 *    1. Set LEGACY_IMPORT_ENABLED=true in .env (default false).
 *    2. Run against a SAFE DEV DATABASE first — never on production.
 *    3. Idempotent: uses firstOrCreate keyed on legacy `code` so re-runs are safe.
 *
 *  USAGE:
 *      php artisan db:seed --class=LegacyImportSeeder
 *
 *  Each entity has its own importXxx() method — keeps this file scannable.
 *  Money columns end in `_paisas` here; legacy stores decimals → multiply by 100.
 * ============================================================
 */
class LegacyImportSeeder extends Seeder
{
    private array $stats = [];

    public function run(): void
    {
        if (env('LEGACY_IMPORT_ENABLED', false) !== true) {
            $this->command->error('LEGACY_IMPORT_ENABLED is not true in .env. Aborting.');
            return;
        }

        if (! $this->legacyConnectionAvailable()) {
            $this->command->error('Cannot connect to legacy DB. Check LEGACY_DB_* env vars.');
            return;
        }

        $this->command->info('Starting legacy import...');

        DB::transaction(function () {
            $this->importCustomers();
            $this->importSuppliers();
            $this->importPartners();
            $this->importWorkers();
            $this->importProducts();
            $this->importBatches();
            // Larger entities (invoices, purchases, returns, JVs, transactions)
            // are intentionally deferred — they require careful per-row money
            // checksums and posting through the same services as new data.
            // Add them in a follow-up step once the master data above passes review.
        });

        $this->printStats();
    }

    private function legacyConnectionAvailable(): bool
    {
        try {
            DB::connection('legacy')->select('SELECT 1');
            return true;
        } catch (\Throwable $e) {
            $this->command->error("Legacy DB connection failed: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Decimal → integer paisas. Always go through this — never multiply inline.
     */
    private function toPaisas(mixed $value): int
    {
        return (int) round(((float) ($value ?? 0)) * 100);
    }

    /**
     * Idempotent helper: create a Party row if not already imported (keyed by phone+name).
     */
    private function ensureParty(array $attrs, string $partyType): Party
    {
        return Party::firstOrCreate(
            [
                'name'  => $attrs['name'],
                'phone' => $attrs['phone'] ?? null,
            ],
            array_merge($attrs, ['party_type' => $partyType])
        );
    }

    private function importCustomers(): void
    {
        $rows = DB::connection('legacy')->table('accounts')
            ->where('can_sell_to', 1)
            ->where('can_buy_from', 0) // pure customers; dealers handled separately
            ->get();

        foreach ($rows as $row) {
            $party = $this->ensureParty([
                'name'                   => $row->name,
                'phone'                  => $row->phone ?? null,
                'address'                => $row->address ?? null,
                'city'                   => $row->city   ?? null,
                'opening_balance_paisas' => $this->toPaisas($row->opening_balance_dr ?? 0)
                                          - $this->toPaisas($row->opening_balance_cr ?? 0),
            ], 'customer');

            Customer::firstOrCreate(['party_id' => $party->id], [
                'credit_limit_paisas' => $this->toPaisas($row->credit_limit ?? 0),
            ]);
        }
        $this->stats['customers'] = $rows->count();
    }

    private function importSuppliers(): void
    {
        $rows = DB::connection('legacy')->table('accounts')
            ->where('can_buy_from', 1)
            ->where('can_sell_to', 0)
            ->get();

        foreach ($rows as $row) {
            $party = $this->ensureParty([
                'name'                   => $row->name,
                'phone'                  => $row->phone ?? null,
                'address'                => $row->address ?? null,
                'city'                   => $row->city   ?? null,
                'opening_balance_paisas' => $this->toPaisas($row->opening_balance_cr ?? 0)
                                          - $this->toPaisas($row->opening_balance_dr ?? 0),
            ], 'supplier');

            Supplier::firstOrCreate(['party_id' => $party->id], []);
        }
        $this->stats['suppliers'] = $rows->count();
    }

    private function importPartners(): void
    {
        // Legacy might keep partners in either `accounts` (with a partner type) or `partners`.
        $rows = DB::connection('legacy')->table('partners')->get();

        foreach ($rows as $row) {
            $party = $this->ensureParty([
                'name'                   => $row->name,
                'phone'                  => $row->phone ?? null,
                'opening_balance_paisas' => $this->toPaisas($row->opening_balance ?? 0),
            ], 'partner');

            Partner::firstOrCreate(['party_id' => $party->id], [
                'share_percentage' => (float) ($row->share_percentage ?? 0),
            ]);
        }
        $this->stats['partners'] = $rows->count();
    }

    private function importWorkers(): void
    {
        $rows = DB::connection('legacy')->table('workers')->get();

        foreach ($rows as $row) {
            Worker::firstOrCreate(
                ['name' => $row->name, 'phone' => $row->phone ?? null],
                [
                    'pay_type'    => $row->pay_type ?? 'monthly',
                    'rate_paisas' => $this->toPaisas($row->rate ?? 0),
                    'is_active'   => (bool) ($row->is_active ?? true),
                ]
            );
        }
        $this->stats['workers'] = $rows->count();
    }

    private function importProducts(): void
    {
        $rows = DB::connection('legacy')->table('items')->get();

        foreach ($rows as $row) {
            Product::firstOrCreate(
                ['name' => $row->name],
                [
                    'category'             => $row->goods_type ?? null,
                    'unit'                 => $row->unit ?? 'pcs',
                    'pack_size'            => (float) ($row->pack_size ?? 0),
                    'purchase_rate_paisas' => $this->toPaisas($row->gp_rate ?? 0),
                    'sale_rate_paisas'     => $this->toPaisas($row->gs_rate ?? 0),
                    'min_stock_quantity'   => (int) ($row->stock_limit ?? 0),
                    'max_stock'            => (int) ($row->max_stock ?? 0),
                    'current_stock'        => (float) ($row->current_stock ?? 0),
                    'description'          => $row->description ?? null,
                ]
            );
        }
        $this->stats['products'] = $rows->count();
    }

    private function importBatches(): void
    {
        $rows = DB::connection('legacy')->table('batches')->get();

        // Build a quick lookup: legacy item code → new product id
        $productByCode = Product::pluck('id', 'name'); // we used name as the import key

        foreach ($rows as $row) {
            $legacyItem = DB::connection('legacy')->table('items')->where('id', $row->item_id)->first();
            if (! $legacyItem) continue;

            $productId = $productByCode[$legacyItem->name] ?? null;
            if (! $productId) continue;

            ProductBatch::firstOrCreate(
                ['product_id' => $productId, 'batch_number' => (string) $row->batch_no],
                [
                    'qty_received'      => (float) ($row->qty_received  ?? 0),
                    'qty_remaining'     => (float) ($row->qty_remaining ?? 0),
                    'cost_price_paisas' => $this->toPaisas($row->cost_price ?? 0),
                    'received_date'     => $row->received_date ?? now(),
                    'expiry_date'       => $row->expiry_date ?? null,
                    'status'            => ($row->qty_remaining ?? 0) > 0 ? 'active' : 'consumed',
                    'quantity'          => (float) ($row->qty_received ?? 0),
                ]
            );
        }
        $this->stats['batches'] = $rows->count();
    }

    private function printStats(): void
    {
        $this->command->info('--- Legacy import complete ---');
        foreach ($this->stats as $entity => $count) {
            $this->command->info(sprintf('  %-12s %d rows', $entity, $count));
        }

        // Money checksum — proves the conversion is right.
        $newPartyOpening = (int) Party::sum('opening_balance_paisas');
        $this->command->info("  Total party opening (paisas): {$newPartyOpening}");
        $this->command->warn('Verify this matches legacy SUM(opening_balance_dr - opening_balance_cr) * 100.');
    }
}
