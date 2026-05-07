<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\SupplierLedgerEntryResource;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\PartyAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(private PartyAccountService $partyAccounts) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Supplier::class);

        $suppliers = Supplier::with('party')
            ->when($request->search, fn($q, $v) =>
                $q->whereHas('party', fn($q2) => $q2->where('name', 'like', "%{$v}%")))
            ->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($suppliers, SupplierResource::class);
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $this->authorize('create', Supplier::class);

        $supplier = $this->partyAccounts->createForSupplier($request->validated());

        return $this->successResponse(new SupplierResource($supplier), 'Supplier created.', 201);
    }

    public function show(Supplier $supplier): JsonResponse
    {
        $this->authorize('view', $supplier);
        return $this->successResponse(new SupplierResource($supplier->load('party')));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $this->authorize('update', $supplier);

        $this->partyAccounts->updateParty($supplier, $request->validated());

        return $this->successResponse(new SupplierResource($supplier->load('party')), 'Supplier updated.');
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $this->authorize('delete', $supplier);

        $this->partyAccounts->deleteWithParty($supplier);
        return $this->successResponse(message: 'Supplier deleted.');
    }

    public function ledger(Supplier $supplier, Request $request): JsonResponse
    {
        $this->authorize('view', $supplier);

        $entries = $supplier->purchaseEntries()
            ->when($request->from, fn($q, $v) => $q->whereDate('date', '>=', $v))
            ->when($request->to,   fn($q, $v) => $q->whereDate('date', '<=', $v))
            ->orderBy('date')->get();

        return $this->successResponse(SupplierLedgerEntryResource::collection($entries));
    }
}
