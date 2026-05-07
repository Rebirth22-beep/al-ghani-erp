<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function sales(Request $request): JsonResponse
    {
        return $this->successResponse($this->service->sales($request->all()));
    }

    public function purchase(Request $request): JsonResponse
    {
        return $this->successResponse($this->service->purchase($request->all()));
    }

    public function stock(Request $request): JsonResponse
    {
        return $this->successResponse($this->service->stock($request->all()));
    }

    public function party(Request $request): JsonResponse
    {
        return $this->successResponse([]);
    }

    public function worker(Request $request): JsonResponse
    {
        return $this->successResponse([]);
    }
}
