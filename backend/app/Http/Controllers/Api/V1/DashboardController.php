<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardStatsResource;
use App\Http\Resources\RecentBillResource;
use App\Http\Resources\SalesChartPointResource;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

/**
 * Dashboard endpoints. All queries live in DashboardService;
 * this controller is the thin "shape and return" layer.
 */
class DashboardController extends Controller
{
    public function __construct(private DashboardService $service) {}

    public function stats(): JsonResponse
    {
        return $this->successResponse(new DashboardStatsResource($this->service->stats()));
    }

    public function salesChart(): JsonResponse
    {
        return $this->successResponse(SalesChartPointResource::collection($this->service->salesChart()));
    }

    public function recentBills(): JsonResponse
    {
        return $this->successResponse(RecentBillResource::collection($this->service->recentBills()));
    }
}
