<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockAlertResource;
use App\Models\StockAlert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', StockAlert::class);

        $alerts = StockAlert::with('product')
            ->orderBy('current_stock')
            ->limit($request->integer('limit', 100))
            ->get();

        return $this->successResponse(StockAlertResource::collection($alerts));
    }
}
