<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentType;
use App\Models\Customer;
use App\Models\SaleInvoice;
use App\Models\StockAlert;

/**
 * Owns every query the Dashboard uses.
 *
 * Why this is a Service:
 *   SKILL.md §4 — "No business logic in Controllers" and "No raw queries in Controllers".
 *   The dashboard runs four aggregation queries; keeping them here means the controller
 *   stays a thin shape-and-return layer.
 */
class DashboardService
{
    /**
     * Returns the four headline numbers + the top 50 stock alerts.
     * Callers shape the response via DashboardStatsResource.
     */
    public function stats(): array
    {
        return [
            'today_sales_paisas' => (int) SaleInvoice::whereDate('date', today())
                ->where('status', InvoiceStatus::Posted)
                ->sum('total_paisas'),
            'outstanding_paisas' => (int) SaleInvoice::where('payment_type', PaymentType::Credit)
                ->where('status', InvoiceStatus::Posted)
                ->sum('total_paisas'),
            'stock_alert_count'  => StockAlert::count(),
            'active_customers'   => Customer::count(),
            'stock_alerts'       => StockAlert::with('product')->limit(50)->get(),
        ];
    }

    /**
     * Returns this calendar year's posted-sales-by-month data points.
     */
    public function salesChart(): array
    {
        return SaleInvoice::where('status', InvoiceStatus::Posted)
            ->whereYear('date', now()->year)
            ->selectRaw('MONTH(date) as month, SUM(total_paisas) as total_paisas')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'label'        => date('M', mktime(0, 0, 0, (int) $row->month, 1)),
                'total_paisas' => (int) $row->total_paisas,
            ])
            ->all();
    }

    /**
     * Last 10 invoices created (any status) for the recent activity widget.
     */
    public function recentBills(): \Illuminate\Database\Eloquent\Collection
    {
        return SaleInvoice::with('customer.party')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }
}
