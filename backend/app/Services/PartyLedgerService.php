<?php

namespace App\Services;

use App\Models\JournalEntryLine;
use App\Models\Party;
use Carbon\Carbon;

/**
 * ============================================================
 *  PartyLedgerService — receivable/payable ledger queries per party
 * ============================================================
 *
 *  What is a ledger?
 *  -----------------
 *  A ledger is the list of every debit/credit transaction posted against an
 *  account (a customer, supplier, partner, cash account, etc.). It tells us
 *  how much that account currently owes us, or how much we owe them.
 *
 *  Sign convention used everywhere in this app (see rules doc Section 17):
 *
 *      balance_paisas = opening_dr − opening_cr + total_debit − total_credit
 *
 *  - Positive balance → DR → "they owe us" (receivable)
 *  - Negative balance → CR → "we owe them" (payable / they have credit)
 *
 *  When to use this service:
 *  - Showing a party's current balance on an invoice or modal
 *  - Generating a party ledger statement (date-range view)
 *  - Calculating a fiscal year's date bounds for reports
 *
 *  Beginner tip:
 *  All amounts in/out of this service are integer paisas — never rupees.
 *  Convert with formatCurrency() / toPaisas() at the UI edges only.
 * ============================================================
 */
class PartyLedgerService
{
    public function __construct(
        // Re-uses the centralized Setting helpers (Section 17 of rules doc).
        // No need to query DB ourselves.
    ) {}

    /**
     * Returns the current fiscal year's start/end dates as Carbon instances.
     *
     * Example: if fiscal_start_month = 7 (July) and today is 2026-08-15,
     * this returns ['start' => 2026-07-01, 'end' => 2027-06-30, 'label' => '2026-2027'].
     */
    public function currentFiscalYear(?Carbon $asOf = null): array
    {
        $asOf       = $asOf ?? Carbon::now();
        $startMonth = \App\Models\Setting::fiscalStartMonth();
        $startYear  = $asOf->month >= $startMonth ? $asOf->year : $asOf->year - 1;

        $start = Carbon::create($startYear,     $startMonth, 1)->startOfDay();
        $end   = Carbon::create($startYear + 1, $startMonth, 1)->subDay()->endOfDay();

        return [
            'start' => $start,
            'end'   => $end,
            'label' => sprintf('%d-%d', $startYear, $startYear + 1),
        ];
    }

    /**
     * Returns a party's running balance in paisas, applying the sign convention.
     *
     * @param int          $partyId  The Party row id
     * @param Carbon|null  $asOf     Optional cutoff date — only count entries on/before this date
     * @return int                   Positive = receivable (DR), negative = payable (CR)
     */
    public function partyBalance(int $partyId, ?Carbon $asOf = null): int
    {
        $party = Party::findOrFail($partyId);

        $opening = (int) ($party->opening_balance_paisas ?? 0);

        // Sum debits & credits posted to journal lines linked to this party (via reference_id).
        // We treat ref_type=Party as the canonical link; other ledger flows can extend later.
        $lines = JournalEntryLine::query()
            ->whereHas('entry', function ($q) use ($asOf, $partyId) {
                $q->where('reference_type', Party::class)
                  ->where('reference_id', $partyId);
                if ($asOf) {
                    $q->whereDate('date', '<=', $asOf->toDateString());
                }
            })
            ->selectRaw('COALESCE(SUM(debit_paisas), 0) as debit, COALESCE(SUM(credit_paisas), 0) as credit')
            ->first();

        $totalDebit  = (int) ($lines->debit  ?? 0);
        $totalCredit = (int) ($lines->credit ?? 0);

        return $opening + $totalDebit - $totalCredit;
    }

    /**
     * Returns a party statement: opening balance + every line within the range + running balance.
     *
     * Used by the Party Ledger page and the printable ledger PDF.
     *
     * @return array{opening_paisas: int, lines: array, closing_paisas: int}
     */
    public function partyStatement(int $partyId, Carbon $from, Carbon $to): array
    {
        $opening = $this->partyBalance($partyId, (clone $from)->subDay());

        $rows = JournalEntryLine::query()
            ->with('entry')
            ->whereHas('entry', function ($q) use ($from, $to, $partyId) {
                $q->where('reference_type', Party::class)
                  ->where('reference_id', $partyId)
                  ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
            })
            ->orderBy('id')
            ->get();

        $running = $opening;
        $lines   = [];

        foreach ($rows as $row) {
            $debit   = (int) $row->debit_paisas;
            $credit  = (int) $row->credit_paisas;
            $running = $running + $debit - $credit;

            $lines[] = [
                'date'           => $row->entry->date->toDateString(),
                'description'    => $row->description ?? $row->entry->description,
                'debit_paisas'   => $debit,
                'credit_paisas'  => $credit,
                'balance_paisas' => $running,
            ];
        }

        return [
            'opening_paisas' => $opening,
            'lines'          => $lines,
            'closing_paisas' => $running,
        ];
    }
}
