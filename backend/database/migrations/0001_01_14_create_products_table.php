<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category', 100)->nullable();
            $table->string('unit', 50);
            $table->decimal('pack_size', 10, 3)->nullable();
            $table->bigInteger('purchase_rate_paisas')->default(0);
            $table->bigInteger('sale_rate_paisas')->default(0);
            $table->integer('min_stock_quantity')->default(0);
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('products'); }
};
