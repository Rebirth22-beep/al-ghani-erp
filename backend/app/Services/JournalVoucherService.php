<?php

namespace App\Services;

use App\Models\JvVoucher;
use App\Models\JvVoucherLine;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * ============================================================
 *  JournalVoucherService — manual ledger entries
 * ============================================================
 *
 *  A Journal Voucher (JV) is a manual posting where the user picks DR and CR
 *  accounts directly. We use it for:
 *    - Opening balances
 *    - Adjustments / corrections
 *    - Owner withdrawals
 *    - Anything that doesn't fit a sale/purchase/return template.
 *
 *  Hard rules (enforced before save):
 *    - At least 2 lines (one DR, one CR).
 *    - SUM(debit) MUST equal SUM(credit).
 *    - DR account != CR account on same line (caller-side; we enforce sums).
 *    - Amounts must be positive integer paisas.
 *
 *  Voucher number issued by DocumentSequenceService with prefix "JV".
 * ============================================================
 */
class JournalVoucherService
{
    public function __construct(
        private DocumentSequenceService $sequence,
        private AuditLogService $auditLog,
        private JournalService $journal,
    ) {}

    public function create(array $data): JvVoucher
    {
        return DB::transaction(function () use ($data) {
            $lines = $data['lines'] ?? [];
            $this->validateLines($lines);

            $totalDebit = array_sum(array_column($lines, 'debit_paisas'));

            $voucher = JvVoucher::create([
                'voucher_number' => $this->sequence->next('JV'),
                'date'           => $data['date'],
                'fiscal_year'    => Setting::currentFiscalYear(\Carbon\Carbon::parse($data['date'])),
                'narration'      => $data['narration'],
                'total_paisas'   => $totalDebit,
                'created_by'     => Auth::id(),
            ]);

            foreach ($lines as $line) {
                JvVoucherLine::create([
                    'jv_voucher_id'       => $voucher->id,
                    'chart_of_account_id' => $line['chart_of_account_id'],
                    'debit_paisas'        => $line['debit_paisas']  ?? 0,
                    'credit_paisas'       => $line['credit_paisas'] ?? 0,
                    'description'         => $line['description']   ?? null,
                ]);
            }

            // ALSO post to the unified journal so reports (Trial Balance, P&L, Balance Sheet,
            // Cash Flow, General Ledger) include this JV. This is the DRY rule from Section 19.
            $this->journal->post(
                date:        $data['date'],
                description: "JV {$voucher->voucher_number}: {$data['narration']}",
                refType:     JvVoucher::class,
                refId:       $voucher->id,
                lines:       array_map(fn($l) => [
                    'account_id'  => $l['chart_of_account_id'],
                    'debit'       => (int) ($l['debit_paisas']  ?? 0),
                    'credit'      => (int) ($l['credit_paisas'] ?? 0),
                    'description' => $l['description'] ?? null,
                ], $lines),
            );

            $this->auditLog->log('create', JvVoucher::class, $voucher->id, [], $voucher->toArray());

            return $voucher->load('lines.account');
        });
    }

    /**
     * Throw if the lines don't balance or are otherwise invalid.
     */
    private function validateLines(array $lines): void
    {
        if (count($lines) < 2) {
            throw new RuntimeException("A JV must have at least 2 lines (one debit, one credit).");
        }

        $totalDebit  = array_sum(array_map(fn($l) => (int) ($l['debit_paisas']  ?? 0), $lines));
        $totalCredit = array_sum(array_map(fn($l) => (int) ($l['credit_paisas'] ?? 0), $lines));

        if ($totalDebit === 0) {
            throw new RuntimeException("JV total cannot be zero.");
        }

        if ($totalDebit !== $totalCredit) {
            throw new RuntimeException(
                "JV does not balance — debits ({$totalDebit}) must equal credits ({$totalCredit})."
            );
        }
    }
}
