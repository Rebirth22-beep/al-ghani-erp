<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Support\Facades\Auth;

class JournalService
{
    /**
     * Post a double-entry journal. Lines must balance (sum debits == sum credits).
     *
     * @param string $date        ISO date string
     * @param string $description
     * @param string $refType     Morph reference class
     * @param int    $refId
     * @param array  $lines       [['account_id' => int, 'debit' => int, 'credit' => int, 'description' => string], ...]
     */
    public function post(string $date, string $description, string $refType, int $refId, array $lines): JournalEntry
    {
        $totalDebit  = array_sum(array_column($lines, 'debit'));
        $totalCredit = array_sum(array_column($lines, 'credit'));

        if ($totalDebit !== $totalCredit) {
            throw new \DomainException("Journal does not balance: debit {$totalDebit} != credit {$totalCredit}");
        }

        $entry = JournalEntry::create([
            'date'           => $date,
            'description'    => $description,
            'reference_type' => $refType,
            'reference_id'   => $refId,
            'created_by'     => Auth::id(),
        ]);

        foreach ($lines as $line) {
            JournalEntryLine::create([
                'journal_entry_id'   => $entry->id,
                'chart_of_account_id'=> $line['account_id'],
                'debit_paisas'       => $line['debit'] ?? 0,
                'credit_paisas'      => $line['credit'] ?? 0,
                'description'        => $line['description'] ?? null,
            ]);
        }

        return $entry;
    }
}
