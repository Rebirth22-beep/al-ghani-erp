<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sale return lines. Each line points back to the original sale_invoice_line
 * so we can read its FIFO allocations from sale_invoice_line_batches and
 * restore stock into the SAME batches.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_return_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('original_line_id')->nullable()->constrained('sale_invoice_lines')->nullOnDelete();
            $table->decimal('quantity', 12, 3);
            $table->string('unit', 50);
            $table->bigInteger('rate_paisas');
            $table->bigInteger('total_paisas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_return_lines');
    }
};
