<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sale return header. Created already-posted (returns are immediate, not draft).
 *
 * `sale_invoice_id` — optional link to the original invoice. When present we enforce caps:
 *   - return_qty <= original_sold_qty - already_returned_qty
 *   - return_rate <= original_sold_rate
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 20)->unique();
            $table->date('date');
            $table->string('fiscal_year', 9)->nullable()->index();
            $table->foreignId('sale_invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('settlement_type', ['cash', 'credit'])->default('credit');
            $table->bigInteger('total_paisas')->default(0);
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_returns');
    }
};
