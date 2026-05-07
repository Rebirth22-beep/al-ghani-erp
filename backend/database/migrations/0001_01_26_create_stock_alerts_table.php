<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('current_stock', 12, 3)->default(0);
            $table->integer('min_stock')->default(0);
            $table->timestamp('alerted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('stock_alerts'); }
};
