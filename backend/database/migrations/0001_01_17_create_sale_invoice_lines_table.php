<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('product_batches')->nullOnDelete();
            $table->decimal('quantity', 12, 3);
            $table->string('unit', 50);
            $table->bigInteger('rate_paisas');
            $table->bigInteger('total_paisas');
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('sale_invoice_lines'); }
};
