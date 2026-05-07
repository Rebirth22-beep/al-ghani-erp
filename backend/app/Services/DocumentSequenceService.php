<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Issues unique, gap-free document numbers per prefix.
 *
 * USAGE (always go through this — never compute Model::max('id') + 1 yourself):
 *
 *     $billNumber = app(DocumentSequenceService::class)->next('INV');
 *     // → "INV-00042"
 *
 * Concurrency: SELECT ... FOR UPDATE locks the counter row inside a transaction,
 * so two simultaneous calls always get different numbers.
 *
 * Active prefixes: INV (sale invoice), PUR (purchase), RET (sale return),
 * PR (purchase return), SP (supplier payment), JV (journal voucher).
 */
class DocumentSequenceService
{
    /** Number is zero-padded to this width: e.g. width 5 → "INV-00042". */
    private const PAD_WIDTH = 5;

    public function next(string $prefix, ?string $fiscalYear = null): string
    {
        $prefix = strtoupper(trim($prefix));

        return DB::transaction(function () use ($prefix, $fiscalYear) {
            // Lock (or create) the counter row for this prefix + fiscal year combo.
            $row = DB::table('document_sequences')
                ->where('prefix', $prefix)
                ->where(function ($q) use ($fiscalYear) {
                    $fiscalYear === null
                        ? $q->whereNull('fiscal_year')
                        : $q->where('fiscal_year', $fiscalYear);
                })
                ->lockForUpdate()
                ->first();

            if ($row === null) {
                $id  = DB::table('document_sequences')->insertGetId([
                    'prefix'      => $prefix,
                    'fiscal_year' => $fiscalYear,
                    'last_number' => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $next = 1;
            } else {
                $next = ((int) $row->last_number) + 1;
                DB::table('document_sequences')
                    ->where('id', $row->id)
                    ->update(['last_number' => $next, 'updated_at' => now()]);
            }

            return $this->format($prefix, $next);
        });
    }

    /**
     * Peek at the next number WITHOUT incrementing. Useful for UI previews.
     * Do NOT use this when actually saving a document — only `next()` is safe under concurrency.
     */
    public function peek(string $prefix, ?string $fiscalYear = null): string
    {
        $prefix = strtoupper(trim($prefix));

        $row = DB::table('document_sequences')
            ->where('prefix', $prefix)
            ->where(function ($q) use ($fiscalYear) {
                $fiscalYear === null
                    ? $q->whereNull('fiscal_year')
                    : $q->where('fiscal_year', $fiscalYear);
            })
            ->first();

        return $this->format($prefix, ((int) ($row->last_number ?? 0)) + 1);
    }

    private function format(string $prefix, int $number): string
    {
        return $prefix . '-' . str_pad((string) $number, self::PAD_WIDTH, '0', STR_PAD_LEFT);
    }
}
