<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductBatchResource;
use App\Models\ProductBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Read-only listing of product batches. Used by Batch Tracking page + report filters.
 * Mutations to batches happen via PurchaseEntryService (creation) and StockService (FIFO).
 */
class BatchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ProductBatch::class);

        $query = ProductBatch::with('product')
            ->orderByDesc('received_date')
            ->orderByDesc('id');

        if ($productId = $request->integer('product_id')) {
            $query->where('product_id', $productId);
        }
        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }
        if ($request->boolean('expiring_soon')) {
            $query->whereDate('expiry_date', '<=', now()->addDays(30))
                  ->whereDate('expiry_date', '>=', now());
        }

        $batches = $query->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($batches, ProductBatchResource::class);
    }

    public function show(ProductBatch $batch): JsonResponse
    {
        $this->authorize('view', $batch);

        return $this->successResponse(new ProductBatchResource($batch->load('product')));
    }
}
