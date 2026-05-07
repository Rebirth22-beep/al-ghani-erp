<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIFO allocations pivot — one row per (sale_invoice_line, batch) consumed at posting.
 *
 * Why:
 *   When a sale line of 50 units is posted, FIFO might draw from 3 different batches
 *   (20 from the oldest, 25 from next, 5 from the third). We need to remember exactly
 *   which batches were consumed for each line, because:
 *     - Sale returns must restore stock into the SAME batches.
 *     - Profit-per-item reports use the actual cost from the actual batches.
 *     - Audit trail: "this bill consumed batch X, Y, Z".
 *
 * Each row stores the qty taken AND the cost_price_paisas at that time
 * (snapshot — batch costs may change later via reconciliation, but historic
 * sale lines must keep the cost they were posted with).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_invoice_line_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_invoice_line_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained('product_batches')->restrictOnDelete();
            $table->decimal('quantity', 12, 3);
            $table->bigInteger('cost_price_paisas');
            $table->timestamps();
            $table->index(['sale_invoice_line_id', 'batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_invoice_line_batches');
    }
};
