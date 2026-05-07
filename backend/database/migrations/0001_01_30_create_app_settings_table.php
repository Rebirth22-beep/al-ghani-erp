<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds `current_stock` to `products` as a denormalized stock cache.
 *
 * Why a denormalized column?
 *   Computing stock from sum(stockMovements.quantity_change) on every read is slow when
 *   we have thousands of items. We update this column inside StockService whenever stock
 *   moves, and reconcile via StockService::recalculate() during imports.
 *
 * (Migration filename kept as 0001_01_30_create_app_settings_table for ordering — the
 *  app_settings concept itself is handled via the existing `settings` table + Setting model
 *  helpers; see Section 17 of the rules doc. This migration adds the missing stock column.)
 */
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'current_stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('current_stock', 14, 3)->default(0)->after('min_stock_quantity');
                $table->integer('max_stock')->nullable()->after('current_stock');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['current_stock', 'max_stock']);
        });
    }
};
