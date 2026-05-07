<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number', 20)->unique();
            $table->date('date');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('payment_type', ['cash', 'credit'])->default('cash');
            $table->enum('season', ['rabi', 'kharif', 'none'])->default('none');
            $table->string('bill_book_number', 50)->nullable();
            $table->bigInteger('total_paisas')->default(0);
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft');
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['date', 'status']);
            $table->index('customer_id');
        });
    }

    public function down(): void { Schema::dropIfExists('sale_invoices'); }
};
