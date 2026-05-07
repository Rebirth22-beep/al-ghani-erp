<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseEntry\StorePurchaseEntryRequest;
use App\Http\Requests\PurchaseEntry\UpdatePurchaseEntryRequest;
use App\Http\Resources\PurchaseEntryResource;
use App\Models\PurchaseEntry;
use App\Services\PurchaseEntryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseEntryController extends Controller
{
    public function __construct(private PurchaseEntryService $service) {}

    public function index(Request $request): JsonResponse
    {
        $entries = PurchaseEntry::with('supplier.party')
            ->when($request->search, fn($q, $v) =>
                $q->where('reference_number', 'like', "%{$v}%")
                  ->orWhereHas('supplier.party', fn($q2) => $q2->where('name', 'like', "%{$v}%"))
            )
            ->orderByDesc('date')
            ->paginate($request->per_page ?? 20);

        return $this->paginatedResponse($entries, PurchaseEntryResource::class);
    }

    public function store(StorePurchaseEntryRequest $request): JsonResponse
    {
        $entry = $this->service->create($request->validated());
        return $this->successResponse(new PurchaseEntryResource($entry), 'Purchase entry created.', 201);
    }

    public function show(PurchaseEntry $purchaseEntry): JsonResponse
    {
        return $this->successResponse(new PurchaseEntryResource($purchaseEntry->load('lines.product', 'supplier.party')));
    }

    public function update(UpdatePurchaseEntryRequest $request, PurchaseEntry $purchaseEntry): JsonResponse
    {
        $purchaseEntry->update($request->validated());
        return $this->successResponse(new PurchaseEntryResource($purchaseEntry->fresh()), 'Updated.');
    }

    public function destroy(PurchaseEntry $purchaseEntry): JsonResponse
    {
        $purchaseEntry->delete();
        return $this->successResponse(message: 'Deleted.');
    }

    public function postEntry(PurchaseEntry $purchaseEntry): JsonResponse
    {
        $entry = $this->service->post($purchaseEntry);
        return $this->successResponse(new PurchaseEntryResource($entry), 'Posted.');
    }
}
