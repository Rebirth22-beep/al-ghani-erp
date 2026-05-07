<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockAdjustment\StoreStockAdjustmentRequest;
use App\Http\Resources\StockAdjustmentResource;
use App\Models\StockAdjustment;
use App\Services\StockAdjustmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function __construct(private StockAdjustmentService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', StockAdjustment::class);

        $adjustments = StockAdjustment::with('product', 'createdBy')
            ->orderByDesc('date')
            ->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($adjustments, StockAdjustmentResource::class);
    }

    public function store(StoreStockAdjustmentRequest $request): JsonResponse
    {
        $this->authorize('create', StockAdjustment::class);

        $adjustment = $this->service->create($request->validated());

        return $this->successResponse(new StockAdjustmentResource($adjustment), 'Adjustment recorded.', 201);
    }

    public function show(StockAdjustment $stockAdjustment): JsonResponse
    {
        $this->authorize('view', $stockAdjustment);
        return $this->successResponse(new StockAdjustmentResource($stockAdjustment->load('product')));
    }

    public function destroy(StockAdjustment $stockAdjustment): JsonResponse
    {
        $this->authorize('delete', $stockAdjustment);

        $this->service->delete($stockAdjustment);
        return $this->successResponse(message: 'Adjustment archived.');
    }
}
