<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Application-wide key/value settings (fiscal_start_month, sales_account_id, etc.).
 * One row = one setting. Always read/write via the static helpers below — never inline queries.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Month (1-12) the fiscal year starts in. Defaults to January.
     */
    public static function fiscalStartMonth(): int
    {
        return (int) static::get('fiscal_start_month', 1);
    }

    /**
     * Current fiscal year label, e.g. "2026-2027".
     * If today is on/after the start month → "thisYear-nextYear". Otherwise "lastYear-thisYear".
     */
    public static function currentFiscalYear(?\DateTimeInterface $asOf = null): string
    {
        $asOf       = $asOf ? \Carbon\Carbon::instance($asOf) : \Carbon\Carbon::now();
        $startMonth = static::fiscalStartMonth();

        $startYear = $asOf->month >= $startMonth ? $asOf->year : $asOf->year - 1;

        return sprintf('%d-%d', $startYear, $startYear + 1);
    }
}
