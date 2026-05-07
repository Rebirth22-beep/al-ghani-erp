<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extends `product_batches` with the columns FIFO logic needs:
 *
 *   qty_received       - what came in originally (audit trail)
 *   qty_remaining      - what's still in stock (FIFO consumes from here)
 *   cost_price_paisas  - per-unit cost when this batch was received
 *   received_date      - oldest-first ordering for FIFO
 *   status             - 'active' | 'consumed' | 'expired'
 *
 * Backfills existing rows so old data still works:
 *   - qty_received  ← quantity (existing column)
 *   - qty_remaining ← quantity
 *   - status        ← 'active'
 *
 * Forward-only on purpose. Down() only drops the new columns; it does NOT
 * restore the old `quantity`-only schema (cleaner than fragile data restoration).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_batches', function (Blueprint $table) {
            $table->decimal('qty_received', 12, 3)->default(0)->after('batch_number');
            $table->decimal('qty_remaining', 12, 3)->default(0)->after('qty_received');
            $table->bigInteger('cost_price_paisas')->default(0)->after('qty_remaining');
            $table->date('received_date')->nullable()->after('cost_price_paisas')->index();
            $table->enum('status', ['active', 'consumed', 'expired'])->default('active')->after('received_date')->index();
        });

        // Backfill from the existing `quantity` column so previous rows still work.
        if (Schema::hasColumn('product_batches', 'quantity')) {
            \DB::statement('UPDATE product_batches SET qty_received = quantity, qty_remaining = quantity WHERE qty_received = 0');
        }
    }

    public function down(): void
    {
        Schema::table('product_batches', function (Blueprint $table) {
            $table->dropColumn(['qty_received', 'qty_remaining', 'cost_price_paisas', 'received_date', 'status']);
        });
    }
};
