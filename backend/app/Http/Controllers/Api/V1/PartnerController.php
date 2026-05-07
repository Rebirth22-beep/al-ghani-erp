<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Partner\StorePartnerRequest;
use App\Http\Requests\Partner\UpdatePartnerRequest;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use App\Services\PartyAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct(private PartyAccountService $partyAccounts) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Partner::class);

        $partners = Partner::with('party')->paginate($request->integer('per_page', 20));
        return $this->paginatedResponse($partners, PartnerResource::class);
    }

    public function store(StorePartnerRequest $request): JsonResponse
    {
        $this->authorize('create', Partner::class);

        $partner = $this->partyAccounts->createForPartner(
            partyData:        $request->validated(),
            sharePercentage:  (float) ($request->share_percentage ?? 0),
        );

        return $this->successResponse(new PartnerResource($partner), 'Partner created.', 201);
    }

    public function show(Partner $partner): JsonResponse
    {
        $this->authorize('view', $partner);
        return $this->successResponse(new PartnerResource($partner->load('party')));
    }

    public function update(UpdatePartnerRequest $request, Partner $partner): JsonResponse
    {
        $this->authorize('update', $partner);

        $this->partyAccounts->updateParty($partner, $request->validated());
        if ($request->filled('share_percentage')) {
            $partner->update(['share_percentage' => $request->share_percentage]);
        }

        return $this->successResponse(new PartnerResource($partner->load('party')), 'Partner updated.');
    }

    public function destroy(Partner $partner): JsonResponse
    {
        $this->authorize('delete', $partner);

        $this->partyAccounts->deleteWithParty($partner);
        return $this->successResponse(message: 'Partner deleted.');
    }

    public function ledger(Partner $partner): JsonResponse
    {
        $this->authorize('view', $partner);
        // Partner ledger to be wired through PartyLedgerService once the partner-as-party
        // link gets a chart_of_account mapping. For now this returns an empty list.
        return $this->successResponse([]);
    }
}
