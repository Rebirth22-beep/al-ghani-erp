<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

/**
 * Locks down the FIFO contract from rules doc §20.
 */
class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_fifo_deducts_from_oldest_batch_first(): void
    {
        $product = Product::create([
            'name'          => 'Test Seed',
            'unit'          => 'kg',
            'current_stock' => 30,
        ]);

        // Two batches: oldest first.
        ProductBatch::create([
            'product_id'        => $product->id,
            'batch_number'      => 'OLD',
            'quantity'          => 10,
            'qty_received'      => 10,
            'qty_remaining'     => 10,
            'cost_price_paisas' => 1000,
            'received_date'     => '2026-01-01',
            'status'            => 'active',
        ]);
        ProductBatch::create([
            'product_id'        => $product->id,
            'batch_number'      => 'NEW',
            'quantity'          => 20,
            'qty_received'      => 20,
            'qty_remaining'     => 20,
            'cost_price_paisas' => 1500,
            'received_date'     => '2026-03-01',
            'status'            => 'active',
        ]);

        $allocations = app(StockService::class)->deductStock($product->id, 15);

        // 10 from OLD, 5 from NEW
        $this->assertCount(2, $allocations);
        $this->assertSame(10.0, $allocations[0]['qty']);
        $this->assertSame(5.0,  $allocations[1]['qty']);

        // OLD is fully consumed
        $this->assertSame('consumed', ProductBatch::where('batch_number', 'OLD')->value('status'));
        // NEW has 15 remaining
        $this->assertSame(15.0, (float) ProductBatch::where('batch_number', 'NEW')->value('qty_remaining'));
    }

    public function test_insufficient_stock_throws(): void
    {
        $product = Product::create(['name' => 'Test', 'unit' => 'kg', 'current_stock' => 0]);

        $this->expectException(RuntimeException::class);
        app(StockService::class)->deductStock($product->id, 5);
    }

    public function test_average_cost_uses_only_active_batches_with_remaining_stock(): void
    {
        $product = Product::create(['name' => 'Test', 'unit' => 'kg', 'current_stock' => 0]);

        // Empty batch should be ignored.
        ProductBatch::create([
            'product_id'        => $product->id,
            'batch_number'      => 'EMPTY',
            'quantity'          => 0,
            'qty_received'      => 10,
            'qty_remaining'     => 0,
            'cost_price_paisas' => 9999,
            'received_date'     => '2026-01-01',
            'status'            => 'consumed',
        ]);
        ProductBatch::create([
            'product_id'        => $product->id,
            'batch_number'      => 'A',
            'quantity'          => 10,
            'qty_received'      => 10,
            'qty_remaining'     => 10,
            'cost_price_paisas' => 1000,
            'received_date'     => '2026-02-01',
            'status'            => 'active',
        ]);
        ProductBatch::create([
            'product_id'        => $product->id,
            'batch_number'      => 'B',
            'quantity'          => 10,
            'qty_received'      => 10,
            'qty_remaining'     => 10,
            'cost_price_paisas' => 2000,
            'received_date'     => '2026-03-01',
            'status'            => 'active',
        ]);

        // (10*1000 + 10*2000) / (10+10) = 30000 / 20 = 1500
        $this->assertSame(1500, app(StockService::class)->averageCost($product->id));
    }
}
