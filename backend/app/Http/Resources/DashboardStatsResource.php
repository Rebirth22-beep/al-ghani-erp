<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shapes the four headline numbers + top stock alerts for the dashboard.
 * Constructed from the array returned by DashboardService::stats().
 */
class DashboardStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'today_sales_paisas' => (int) $this['today_sales_paisas'],
            'outstanding_paisas' => (int) $this['outstanding_paisas'],
            'stock_alert_count'  => (int) $this['stock_alert_count'],
            'active_customers'   => (int) $this['active_customers'],
            'stock_alerts'       => collect($this['stock_alerts'])->map(fn($a) => [
                'product_name' => $a->product?->name,
                'stock'        => (float) $a->current_stock,
            ])->all(),
        ];
    }
}
