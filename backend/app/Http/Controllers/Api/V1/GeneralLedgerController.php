<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\GeneralLedgerRequest;
use App\Http\Resources\GeneralLedgerLineResource;
use App\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;

class GeneralLedgerController extends Controller
{
    public function index(GeneralLedgerRequest $request): JsonResponse
    {
        $this->authorizeReportAccess();

        $filters = $request->validated();

        $lines = JournalEntryLine::with('entry', 'chartOfAccount')
            ->when($filters['from'] ?? null, fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '>=', $v)))
            ->when($filters['to'] ?? null, fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '<=', $v)))
            ->when($filters['account_id'] ?? null, fn($q, $v) => $q->where('chart_of_account_id', $v))
            ->orderBy('id')
            ->paginate($filters['per_page'] ?? 50);

        return $this->paginatedResponse($lines, GeneralLedgerLineResource::class);
    }
}
