<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jv_voucher_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jv_voucher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chart_of_account_id')->constrained()->restrictOnDelete();
            $table->bigInteger('debit_paisas')->default(0);
            $table->bigInteger('credit_paisas')->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jv_voucher_lines');
    }
};
