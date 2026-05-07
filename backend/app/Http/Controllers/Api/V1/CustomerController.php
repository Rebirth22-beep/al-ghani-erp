<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerLedgerInvoiceResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\PartyAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(private PartyAccountService $partyAccounts) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Customer::class);

        $customers = Customer::with('party')
            ->when($request->search, fn($q, $v) =>
                $q->whereHas('party', fn($q2) => $q2->where('name', 'like', "%{$v}%")->orWhere('phone', 'like', "%{$v}%"))
            )
            ->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($customers, CustomerResource::class);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $this->authorize('create', Customer::class);

        $customer = $this->partyAccounts->createForCustomer(
            partyData:         $request->validated(),
            creditLimitPaisas: (int) ($request->credit_limit_paisas ?? 0),
        );

        return $this->successResponse(new CustomerResource($customer), 'Customer created.', 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        $this->authorize('view', $customer);
        return $this->successResponse(new CustomerResource($customer->load('party')));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $this->authorize('update', $customer);

        $this->partyAccounts->updateParty($customer, $request->validated());
        $customer->update($request->only('credit_limit_paisas'));

        return $this->successResponse(new CustomerResource($customer->load('party')), 'Customer updated.');
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->authorize('delete', $customer);

        $this->partyAccounts->deleteWithParty($customer);
        return $this->successResponse(message: 'Customer deleted.');
    }

    public function ledger(Customer $customer, Request $request): JsonResponse
    {
        $this->authorize('view', $customer);

        $invoices = $customer->saleInvoices()
            ->with('lines')
            ->when($request->from, fn($q, $v) => $q->whereDate('date', '>=', $v))
            ->when($request->to,   fn($q, $v) => $q->whereDate('date', '<=', $v))
            ->orderBy('date')
            ->get();

        return $this->successResponse(CustomerLedgerInvoiceResource::collection($invoices));
    }
}
