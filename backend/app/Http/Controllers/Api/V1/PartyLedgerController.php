<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartyLedger\PartyLedgerRequest;
use App\Http\Resources\PartyLedgerStatementResource;
use App\Services\PartyLedgerService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Party Ledger endpoint - returns the receivable/payable statement for one party.
 * All math goes through PartyLedgerService (sign convention applied there).
 */
class PartyLedgerController extends Controller
{
    public function __construct(private PartyLedgerService $ledger) {}

    public function index(PartyLedgerRequest $request): JsonResponse
    {
        $from = $request->date('from') ? Carbon::parse($request->date('from')) : Carbon::now()->startOfMonth();
        $to   = $request->date('to')   ? Carbon::parse($request->date('to'))   : Carbon::now()->endOfDay();

        $statement = $this->ledger->partyStatement($request->integer('party_id'), $from, $to);

        return $this->successResponse(new PartyLedgerStatementResource($statement));
    }
}
