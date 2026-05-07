<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralLedgerLineResource;
use App\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $lines = JournalEntryLine::with('entry', 'chartOfAccount')
            ->when($request->from, fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '>=', $v)))
            ->when($request->to,   fn($q, $v) => $q->whereHas('entry', fn($q2) => $q2->whereDate('date', '<=', $v)))
            ->when($request->account_id, fn($q, $v) => $q->where('chart_of_account_id', $v))
            ->orderBy('id')
            ->paginate($request->per_page ?? 50);

        return $this->paginatedResponse($lines, GeneralLedgerLineResource::class);
    }
}
