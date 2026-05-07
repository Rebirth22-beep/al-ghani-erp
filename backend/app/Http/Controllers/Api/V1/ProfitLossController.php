<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportDateRangeRequest;
use App\Http\Resources\ProfitLossResource;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * P&L = Revenue − Expense, computed from posted journal entries
 * across the requested date range. Uses single-pass aggregation (no N+1).
 */
class ProfitLossController extends Controller
{
    public function index(ReportDateRangeRequest $request): JsonResponse
    {

        $from = $request->date('from')?->toDateString();
        $to   = $request->date('to')?->toDateString();

        $aggregates = JournalEntryLine::select(
                'chart_of_account_id',
                DB::raw('SUM(debit_paisas)  as debit'),
                DB::raw('SUM(credit_paisas) as credit')
            )
            ->whereHas('entry', function ($q) use ($from, $to) {
                if ($from) $q->whereDate('date', '>=', $from);
                if ($to)   $q->whereDate('date', '<=', $to);
            })
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $revenue  = ['lines' => [], 'total_paisas' => 0];
        $expenses = ['lines' => [], 'total_paisas' => 0];

        foreach (ChartOfAccount::orderBy('code')->get() as $account) {
            $row    = $aggregates->get($account->id);
            $debit  = (int) ($row->debit  ?? 0);
            $credit = (int) ($row->credit ?? 0);

            if ($account->account_type === 'revenue') {
                $balance = $credit - $debit;          // Revenue: credits increase
                $revenue['lines'][] = ['account' => $account->name, 'paisas' => $balance];
                $revenue['total_paisas'] += $balance;
            } elseif ($account->account_type === 'expense') {
                $balance = $debit - $credit;          // Expense: debits increase
                $expenses['lines'][] = ['account' => $account->name, 'paisas' => $balance];
                $expenses['total_paisas'] += $balance;
            }
        }

        return $this->successResponse(new ProfitLossResource([
            'revenue'           => $revenue,
            'expenses'          => $expenses,
            'net_profit_paisas' => $revenue['total_paisas'] - $expenses['total_paisas'],
        ]));
    }
}
