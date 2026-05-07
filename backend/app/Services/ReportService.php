<?php

namespace App\Services;

use App\Models\SaleInvoice;
use App\Models\PurchaseEntry;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function sales(array $filters): array
    {
        return SaleInvoice::with('customer.party', 'lines.product')
            ->when($filters['from'] ?? null, fn($q, $v) => $q->whereDate('date', '>=', $v))
            ->when($filters['to']   ?? null, fn($q, $v) => $q->whereDate('date', '<=', $v))
            ->when($filters['season'] ?? null, fn($q, $v) => $q->where('season', $v))
            ->where('status', 'posted')
            ->orderBy('date')
            ->limit($filters['limit'] ?? 2000)
            ->get()
            ->toArray();
    }

    public function purchase(array $filters): array
    {
        return PurchaseEntry::with('supplier.party', 'lines.product')
            ->when($filters['from'] ?? null, fn($q, $v) => $q->whereDate('date', '>=', $v))
            ->when($filters['to']   ?? null, fn($q, $v) => $q->whereDate('date', '<=', $v))
            ->where('status', 'posted')
            ->orderBy('date')
            ->limit($filters['limit'] ?? 2000)
            ->get()
            ->toArray();
    }

    public function stock(array $filters): array
    {
        return StockMovement::with('product')
            ->when($filters['product_id'] ?? null, fn($q, $v) => $q->where('product_id', $v))
            ->when($filters['from'] ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['to']   ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->orderBy('created_at')
            ->limit($filters['limit'] ?? 5000)
            ->get()
            ->toArray();
    }
}
