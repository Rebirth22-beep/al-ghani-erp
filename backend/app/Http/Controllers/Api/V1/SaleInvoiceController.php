<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvoiceAlreadyPostedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaleInvoice\StoreSaleInvoiceRequest;
use App\Http\Requests\SaleInvoice\UpdateSaleInvoiceRequest;
use App\Http\Resources\SaleInvoiceResource;
use App\Models\SaleInvoice;
use App\Services\SaleInvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleInvoiceController extends Controller
{
    public function __construct(private SaleInvoiceService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', SaleInvoice::class);

        $invoices = SaleInvoice::with('customer.party', 'lines.product')
            ->when($request->search, fn($q, $v) => $q->where('bill_number', 'like', "%{$v}%")
                ->orWhereHas('customer.party', fn($q2) => $q2->where('name', 'like', "%{$v}%")))
            ->orderByDesc('date')
            ->paginate($request->per_page ?? 20);

        return $this->paginatedResponse($invoices, SaleInvoiceResource::class);
    }

    public function store(StoreSaleInvoiceRequest $request): JsonResponse
    {
        $this->authorize('create', SaleInvoice::class);

        $invoice = $this->service->create($request->validated());

        return $this->successResponse(new SaleInvoiceResource($invoice), 'Invoice created.', 201);
    }

    public function show(SaleInvoice $saleInvoice): JsonResponse
    {
        $this->authorize('view', $saleInvoice);

        return $this->successResponse(new SaleInvoiceResource($saleInvoice->load('lines.product', 'customer.party')));
    }

    public function update(UpdateSaleInvoiceRequest $request, SaleInvoice $saleInvoice): JsonResponse
    {
        $this->authorize('update', $saleInvoice);

        $saleInvoice->update($request->only(array_keys($request->except('lines'))));
        if ($request->has('lines')) {
            $this->service->updateLines($saleInvoice, $request->lines);
        }

        return $this->successResponse(
            new SaleInvoiceResource($saleInvoice->fresh('lines.product', 'customer.party')),
            'Invoice updated.'
        );
    }

    public function destroy(SaleInvoice $saleInvoice): JsonResponse
    {
        $this->authorize('delete', $saleInvoice);

        $saleInvoice->delete();

        return $this->successResponse(message: 'Invoice deleted.');
    }

    public function postInvoice(SaleInvoice $saleInvoice): JsonResponse
    {
        $this->authorize('post', $saleInvoice);

        try {
            $invoice = $this->service->post($saleInvoice);
        } catch (InvoiceAlreadyPostedException|InsufficientStockException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }

        return $this->successResponse(new SaleInvoiceResource($invoice), 'Invoice posted.');
    }

    public function partyWise(int $partyId): JsonResponse
    {
        $this->authorize('viewAny', SaleInvoice::class);

        $invoices = SaleInvoice::with('lines.product')
            ->whereHas('customer.party', fn($q) => $q->where('id', $partyId))
            ->orderByDesc('date')
            ->get();

        return $this->successResponse(SaleInvoiceResource::collection($invoices));
    }
}
