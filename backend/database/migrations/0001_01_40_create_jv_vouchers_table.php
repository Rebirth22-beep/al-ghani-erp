<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Journal Voucher header. Each voucher = one JV with multiple lines (DR/CR).
 * Posting goes through JournalVoucherService which validates DR == CR before save.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('jv_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number', 20)->unique();
            $table->date('date');
            $table->string('fiscal_year', 9)->nullable()->index();
            $table->text('narration');
            $table->bigInteger('total_paisas')->default(0); // sum of debits == sum of credits
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jv_vouchers');
    }
};
