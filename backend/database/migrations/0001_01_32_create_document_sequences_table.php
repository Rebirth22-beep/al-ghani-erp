<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Atomic counter table used by DocumentSequenceService.
 *
 * One row per (prefix, fiscal_year). When two devices save at the same instant we use
 * SELECT ... FOR UPDATE so each gets a distinct number.
 *
 * `last_number` starts at 0 and increments to 1, 2, 3, ... on each next() call.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 10);                            // INV, PUR, RET, PR, SP, JV
            $table->string('fiscal_year', 9)->nullable();            // null = global counter
            $table->unsignedBigInteger('last_number')->default(0);
            $table->timestamps();

            $table->unique(['prefix', 'fiscal_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_sequences');
    }
};
