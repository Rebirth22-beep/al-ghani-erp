<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesReturn\StoreSalesReturnRequest;
use App\Http\Resources\SalesReturnResource;
use App\Models\SalesReturn;
use App\Services\SalesReturnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class SalesReturnController extends Controller
{
    public function __construct(private SalesReturnService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', SalesReturn::class);

        $query = SalesReturn::with('customer.party', 'invoice')
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($search = $request->string('search')->toString()) {
            $query->where('return_number', 'like', "%{$search}%");
        }
        if ($from = $request->date('from')) { $query->whereDate('date', '>=', $from); }
        if ($to   = $request->date('to'))   { $query->whereDate('date', '<=', $to); }

        $returns = $query->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($returns, SalesReturnResource::class);
    }

    public function show(SalesReturn $salesReturn): JsonResponse
    {
        $this->authorize('view', $salesReturn);

        return $this->successResponse(new SalesReturnResource($salesReturn->load('lines.product', 'customer.party', 'invoice')));
    }

    public function store(StoreSalesReturnRequest $request): JsonResponse
    {
        $this->authorize('create', SalesReturn::class);

        try {
            $return = $this->service->create($request->validated());
        } catch (RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }

        return $this->successResponse(new SalesReturnResource($return), "Sale return {$return->return_number} created.", 201);
    }

    public function destroy(SalesReturn $salesReturn): JsonResponse
    {
        $this->authorize('delete', $salesReturn);

        // For v1: soft-delete only. Stock reversal of a posted return is a separate ops procedure.
        $salesReturn->delete();
        return $this->successResponse(message: 'Return archived.');
    }
}
