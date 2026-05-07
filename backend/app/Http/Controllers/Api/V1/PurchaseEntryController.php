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
        $this->authorize('viewAny', PurchaseEntry::class);

        $entries = PurchaseEntry::with('supplier.party')
            ->when($request->search, fn($q, $v) =>
                $q->where('reference_number', 'like', "%{$v}%")
                  ->orWhereHas('supplier.party', fn($q2) => $q2->where('name', 'like', "%{$v}%"))
            )
            ->orderByDesc('date')
            ->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($entries, PurchaseEntryResource::class);
    }

    public function store(StorePurchaseEntryRequest $request): JsonResponse
    {
        $this->authorize('create', PurchaseEntry::class);

        $entry = $this->service->create($request->validated());
        return $this->successResponse(new PurchaseEntryResource($entry), 'Purchase entry created.', 201);
    }

    public function show(PurchaseEntry $purchaseEntry): JsonResponse
    {
        $this->authorize('view', $purchaseEntry);
        return $this->successResponse(new PurchaseEntryResource($purchaseEntry->load('lines.product', 'supplier.party')));
    }

    public function update(UpdatePurchaseEntryRequest $request, PurchaseEntry $purchaseEntry): JsonResponse
    {
        $this->authorize('update', $purchaseEntry);

        $purchaseEntry->update($request->validated());
        return $this->successResponse(new PurchaseEntryResource($purchaseEntry->fresh()), 'Purchase entry updated.');
    }

    public function destroy(PurchaseEntry $purchaseEntry): JsonResponse
    {
        $this->authorize('delete', $purchaseEntry);

        $purchaseEntry->delete();
        return $this->successResponse(message: 'Purchase entry deleted.');
    }

    public function postEntry(PurchaseEntry $purchaseEntry): JsonResponse
    {
        $this->authorize('post', $purchaseEntry);

        $entry = $this->service->post($purchaseEntry);
        return $this->successResponse(new PurchaseEntryResource($entry), 'Purchase entry posted.');
    }
}
