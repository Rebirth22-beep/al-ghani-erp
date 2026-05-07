<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SaleInvoice;
use App\Models\StockAlert;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $todaySales = SaleInvoice::whereDate('date', today())
            ->where('status', 'posted')
            ->sum('total_paisas');

        $outstanding = SaleInvoice::where('payment_type', 'credit')
            ->where('status', 'posted')
            ->sum('total_paisas');

        $stockAlerts = StockAlert::with('product')->limit(50)->get();

        return $this->successResponse([
            'today_sales_paisas' => $todaySales,
            'outstanding_paisas' => $outstanding,
            'stock_alert_count'  => StockAlert::count(),
            'active_customers'   => Customer::count(),
            'stock_alerts'       => $stockAlerts->map(fn($a) => [
                'product_name' => $a->product->name,
                'stock'        => $a->current_stock,
            ]),
        ]);
    }

    public function salesChart(): JsonResponse
    {
        $data = SaleInvoice::where('status', 'posted')
            ->whereYear('date', now()->year)
            ->selectRaw('MONTH(date) as month, SUM(total_paisas) as total_paisas')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'label'        => date('M', mktime(0, 0, 0, $row->month, 1)),
                'total_paisas' => (int) $row->total_paisas,
            ]);

        return $this->successResponse($data);
    }

    public function recentBills(): JsonResponse
    {
        $bills = SaleInvoice::with('customer.party')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn($inv) => [
                'id'           => $inv->id,
                'bill_number'  => $inv->bill_number,
                'party_name'   => $inv->customer?->party?->name,
                'date'         => $inv->date,
                'total_paisas' => $inv->total_paisas,
                'status'       => $inv->status->value,
            ]);

        return $this->successResponse($bills);
    }
}
