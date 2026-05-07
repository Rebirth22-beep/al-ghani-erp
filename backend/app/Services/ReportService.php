<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\PurchaseEntry;
use App\Models\SaleInvoice;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;

/**
 * Read-side report queries. Returns Eloquent collections so callers
 * can wrap them in standard Resource classes (SKILL.md §4).
 *
 * All filters are passed as a plain array — `from`, `to`, `season`,
 * `product_id`, `limit`. Each filter is optional.
 */
class ReportService
{
    public function sales(array $filters): Collection
    {
        return SaleInvoice::with('customer.party', 'lines.product')
            ->when($filters['from']   ?? null, fn($q, $v) => $q->whereDate('date', '>=', $v))
            ->when($filters['to']     ?? null, fn($q, $v) => $q->whereDate('date', '<=', $v))
            ->when($filters['season'] ?? null, fn($q, $v) => $q->where('season', $v))
            ->where('status', InvoiceStatus::Posted)
            ->orderBy('date')
            ->limit($filters['limit'] ?? 2000)
            ->get();
    }

    public function purchase(array $filters): Collection
    {
        return PurchaseEntry::with('supplier.party', 'lines.product')
            ->when($filters['from'] ?? null, fn($q, $v) => $q->whereDate('date', '>=', $v))
            ->when($filters['to']   ?? null, fn($q, $v) => $q->whereDate('date', '<=', $v))
            ->where('status', InvoiceStatus::Posted)
            ->orderBy('date')
            ->limit($filters['limit'] ?? 2000)
            ->get();
    }

    public function stock(array $filters): Collection
    {
        return StockMovement::with('product')
            ->when($filters['product_id'] ?? null, fn($q, $v) => $q->where('product_id', $v))
            ->when($filters['from']       ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['to']         ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->orderBy('created_at')
            ->limit($filters['limit'] ?? 5000)
            ->get();
    }
}
