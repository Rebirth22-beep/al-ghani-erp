<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\BalanceSheetRequest;
use App\Http\Resources\BalanceSheetResource;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Balance Sheet at a moment in time: Assets, Liabilities, Equity.
 * Equation that must hold: Assets = Liabilities + Equity.
 */
class BalanceSheetController extends Controller
{
    public function index(BalanceSheetRequest $request): JsonResponse
    {
        $this->authorizeReportAccess();

        $asOf = $request->date('as_of')?->toDateString() ?? now()->toDateString();

        $aggregates = JournalEntryLine::select(
                'chart_of_account_id',
                DB::raw('SUM(debit_paisas)  as debit'),
                DB::raw('SUM(credit_paisas) as credit')
            )
            ->whereHas('entry', fn($q) => $q->whereDate('date', '<=', $asOf))
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $sections = ['asset' => [], 'liability' => [], 'equity' => []];
        $totals   = ['asset' => 0, 'liability' => 0, 'equity' => 0];

        foreach (ChartOfAccount::orderBy('code')->get() as $account) {
            if (! in_array($account->account_type, ['asset', 'liability', 'equity'])) continue;

            $row    = $aggregates->get($account->id);
            $debit  = (int) ($row->debit  ?? 0);
            $credit = (int) ($row->credit ?? 0);

            // Asset: DR positive. Liability/Equity: CR positive.
            $balance = $account->account_type === 'asset' ? ($debit - $credit) : ($credit - $debit);

            if ($balance !== 0) {
                $sections[$account->account_type][] = ['account' => $account->name, 'paisas' => $balance];
                $totals[$account->account_type]    += $balance;
            }
        }

        return $this->successResponse(new BalanceSheetResource([
            'as_of'       => $asOf,
            'assets'      => ['lines' => $sections['asset'],     'total_paisas' => $totals['asset']],
            'liabilities' => ['lines' => $sections['liability'], 'total_paisas' => $totals['liability']],
            'equity'      => ['lines' => $sections['equity'],    'total_paisas' => $totals['equity']],
            'balanced'    => $totals['asset'] === ($totals['liability'] + $totals['equity']),
        ]));
    }
}
