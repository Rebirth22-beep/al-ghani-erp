<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a `fiscal_year` column (e.g. "2026-2027") to every business-document table
 * so reports can be filtered per fiscal year. Existing rows stay null until backfilled.
 *
 * One forward-only migration on purpose so we don't edit any of the original `create_*_table` files.
 */
return new class extends Migration {
    /** Tables that need a fiscal_year column. */
    private const TABLES = [
        'sale_invoices',
        'purchase_entries',
        'journal_entries',
        // sales_returns + purchase_returns will gain the column when their tables are created in Phase 4.
    ];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'fiscal_year')) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) {
                $t->string('fiscal_year', 9)->nullable()->after('date')->index();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'fiscal_year')) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) {
                $t->dropIndex([$t->getTable() . '_fiscal_year_index']);
                $t->dropColumn('fiscal_year');
            });
        }
    }
};
