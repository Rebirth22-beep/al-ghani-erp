<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportDateRangeRequest;
use App\Http\Resources\CashFlowLineResource;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;

/**
 * Cash Flow = movements in/out of cash and bank accounts for a date range.
 * Filters journal lines whose chart_of_account is of type 'asset' AND name contains 'cash' or 'bank'.
 * (Future: tag accounts with a `cash_or_bank` flag for cleaner filtering.)
 *
 * Access (SKILL.md §9): Admin + Accountant only.
 */
class CashFlowController extends Controller
{
    public function index(ReportDateRangeRequest $request): JsonResponse
    {
        $this->authorizeReportAccess();

        $from = $request->date('from')?->toDateString();
        $to   = $request->date('to')?->toDateString();

        $cashAccountIds = ChartOfAccount::where('account_type', 'asset')
            ->where(fn($q) => $q->where('name', 'like', '%cash%')->orWhere('name', 'like', '%bank%'))
            ->pluck('id');

        $lines = JournalEntryLine::with(['entry', 'chartOfAccount'])
            ->whereIn('chart_of_account_id', $cashAccountIds)
            ->whereHas('entry', function ($q) use ($from, $to) {
                if ($from) $q->whereDate('date', '>=', $from);
                if ($to)   $q->whereDate('date', '<=', $to);
            })
            ->orderBy('id')
            ->get();

        $running = 0;
        $rows    = [];
        foreach ($lines as $l) {
            $in   = (int) $l->debit_paisas;
            $out  = (int) $l->credit_paisas;
            $running += $in - $out;
            $rows[] = [
                'date'           => $l->entry->date->toDateString(),
                'account'        => $l->chartOfAccount->name,
                'description'    => $l->description ?? $l->entry->description,
                'in_paisas'      => $in,
                'out_paisas'     => $out,
                'balance_paisas' => $running,
            ];
        }

        return $this->successResponse(CashFlowLineResource::collection(collect($rows)));
    }
}
