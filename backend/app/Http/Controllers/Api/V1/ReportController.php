<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseEntryResource;
use App\Http\Resources\SaleInvoiceResource;
use App\Http\Resources\StockMovementResource;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Read-only report endpoints. Each method:
 *   1. Reads filters from the Request (all filters optional).
 *   2. Calls ReportService for the data.
 *   3. Wraps rows in their domain Resource so the response shape matches other endpoints.
 */
class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function sales(Request $request): JsonResponse
    {
        return $this->successResponse(
            SaleInvoiceResource::collection($this->service->sales($request->all()))
        );
    }

    public function purchase(Request $request): JsonResponse
    {
        return $this->successResponse(
            PurchaseEntryResource::collection($this->service->purchase($request->all()))
        );
    }

    public function stock(Request $request): JsonResponse
    {
        return $this->successResponse(
            StockMovementResource::collection($this->service->stock($request->all()))
        );
    }

    public function party(Request $request): JsonResponse
    {
        // Party report wiring deferred: PartyLedgerService already exposes the data.
        return $this->successResponse([]);
    }

    public function worker(Request $request): JsonResponse
    {
        // Worker report wiring deferred until WorkerKhataService grows monthly summary.
        return $this->successResponse([]);
    }
}
