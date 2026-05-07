<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseReturn\StorePurchaseReturnRequest;
use App\Http\Resources\PurchaseReturnResource;
use App\Models\PurchaseReturn;
use App\Services\PurchaseReturnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class PurchaseReturnController extends Controller
{
    public function __construct(private PurchaseReturnService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', PurchaseReturn::class);

        $query = PurchaseReturn::with('supplier.party', 'purchase')
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($search = $request->string('search')->toString()) {
            $query->where('return_number', 'like', "%{$search}%");
        }
        if ($from = $request->date('from')) { $query->whereDate('date', '>=', $from); }
        if ($to   = $request->date('to'))   { $query->whereDate('date', '<=', $to); }

        $returns = $query->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($returns, PurchaseReturnResource::class);
    }

    public function show(PurchaseReturn $purchaseReturn): JsonResponse
    {
        $this->authorize('view', $purchaseReturn);

        return $this->successResponse(new PurchaseReturnResource($purchaseReturn->load('lines.product', 'supplier.party', 'purchase')));
    }

    public function store(StorePurchaseReturnRequest $request): JsonResponse
    {
        $this->authorize('create', PurchaseReturn::class);

        try {
            $return = $this->service->create($request->validated());
        } catch (RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }

        return $this->successResponse(new PurchaseReturnResource($return), "Purchase return {$return->return_number} created.", 201);
    }

    public function destroy(PurchaseReturn $purchaseReturn): JsonResponse
    {
        $this->authorize('delete', $purchaseReturn);

        $purchaseReturn->delete();
        return $this->successResponse(message: 'Return archived.');
    }
}
