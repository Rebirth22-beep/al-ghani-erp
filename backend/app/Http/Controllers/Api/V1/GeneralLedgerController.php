<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportDateRangeRequest;
use App\Http\Resources\GeneralLedgerLineResource;
use App\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;

class GeneralLedgerController extends Controller
{
    public function index(ReportDateRangeRequest $request): JsonResponse
    {
        $this->authorizeReportAccess();

        $lines = JournalEntryLine::with('entry', 'chartOfAccount')
            ->when($request->from, fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '>=', $v)))
            ->when($request->to,   fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '<=', $v)))
            ->when($request->account_id, fn($q, $v) => $q->where('chart_of_account_id', $v))
            ->orderBy('id')
            ->paginate($request->integer('per_page', 50));

        return $this->paginatedResponse($lines, GeneralLedgerLineResource::class);
    }
}
