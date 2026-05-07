<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportDateRangeRequest;
use App\Http\Resources\TrialBalanceLineResource;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function index(ReportDateRangeRequest $request): JsonResponse
    {
        $this->authorizeReportAccess();

        // Single aggregation query replaces N per-account queries
        $aggregates = JournalEntryLine::select(
                'chart_of_account_id',
                DB::raw('SUM(debit_paisas) as total_debit'),
                DB::raw('SUM(credit_paisas) as total_credit')
            )
            ->when($request->from, fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '>=', $v)))
            ->when($request->to,   fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '<=', $v)))
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $accounts = ChartOfAccount::get()
            ->map(function ($account) use ($aggregates) {
                $row = $aggregates->get($account->id);
                return [
                    'account_id'    => $account->id,
                    'account_code'  => $account->code,
                    'account_name'  => $account->name,
                    'account_type'  => $account->account_type,
                    'total_debit'   => (int) ($row->total_debit ?? 0),
                    'total_credit'  => (int) ($row->total_credit ?? 0),
                ];
            })
            ->filter(fn($a) => $a['total_debit'] > 0 || $a['total_credit'] > 0)
            ->values();

        return $this->successResponse(TrialBalanceLineResource::collection($accounts));
    }
}
