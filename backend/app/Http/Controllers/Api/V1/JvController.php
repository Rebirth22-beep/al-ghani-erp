<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jv\StoreJvRequest;
use App\Http\Resources\JvVoucherResource;
use App\Models\JvVoucher;
use App\Services\JournalVoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class JvController extends Controller
{
    public function __construct(private JournalVoucherService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', JvVoucher::class);

        $query = JvVoucher::with('lines.account')
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($search = $request->string('search')->toString()) {
            $query->where('voucher_number', 'like', "%{$search}%");
        }
        if ($from = $request->date('from')) { $query->whereDate('date', '>=', $from); }
        if ($to   = $request->date('to'))   { $query->whereDate('date', '<=', $to); }

        $rows = $query->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($rows, JvVoucherResource::class);
    }

    public function show(JvVoucher $jv): JsonResponse
    {
        $this->authorize('view', $jv);

        return $this->successResponse(new JvVoucherResource($jv->load('lines.account')));
    }

    public function store(StoreJvRequest $request): JsonResponse
    {
        $this->authorize('create', JvVoucher::class);

        try {
            $voucher = $this->service->create($request->validated());
        } catch (RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }

        return $this->successResponse(new JvVoucherResource($voucher), "JV {$voucher->voucher_number} created.", 201);
    }

    public function destroy(JvVoucher $jv): JsonResponse
    {
        $this->authorize('delete', $jv);

        $jv->delete();
        return $this->successResponse(message: 'JV archived.');
    }
}
