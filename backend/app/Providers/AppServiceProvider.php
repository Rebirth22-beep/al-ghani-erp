<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\JvVoucher;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\PurchaseEntry;
use App\Models\PurchaseReturn;
use App\Models\SaleInvoice;
use App\Models\SalesReturn;
use App\Models\StockAdjustment;
use App\Models\StockAlert;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerAttendance;
use App\Models\WorkerSalary;
use App\Policies\CustomerPolicy;
use App\Policies\JvVoucherPolicy;
use App\Policies\PartnerPolicy;
use App\Policies\ProductBatchPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PurchaseEntryPolicy;
use App\Policies\PurchaseReturnPolicy;
use App\Policies\SaleInvoicePolicy;
use App\Policies\SalesReturnPolicy;
use App\Policies\StockAdjustmentPolicy;
use App\Policies\StockAlertPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkerAttendancePolicy;
use App\Policies\WorkerPolicy;
use App\Policies\WorkerSalaryPolicy;
use App\Services\DocumentSequenceService;
use App\Services\PartyLedgerService;
use App\Services\StockService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Cross-cutting services — bind as singletons so the same instance is reused per request.
        // Beginner note: anywhere in the app you can now do
        //     app(StockService::class)->deductStock(...)
        // and Laravel will inject the same instance every time.
        $this->app->singleton(DocumentSequenceService::class);
        $this->app->singleton(PartyLedgerService::class);
        $this->app->singleton(StockService::class);
    }

    public function boot(): void
    {
        // Resource → Policy mapping. Every model exposed via API has an entry here.
        // Adding a new resource? Add the policy AND register it here.
        $policies = [
            Customer::class          => CustomerPolicy::class,
            JvVoucher::class         => JvVoucherPolicy::class,
            Partner::class           => PartnerPolicy::class,
            Product::class           => ProductPolicy::class,
            ProductBatch::class      => ProductBatchPolicy::class,
            PurchaseEntry::class     => PurchaseEntryPolicy::class,
            PurchaseReturn::class    => PurchaseReturnPolicy::class,
            SaleInvoice::class       => SaleInvoicePolicy::class,
            SalesReturn::class       => SalesReturnPolicy::class,
            StockAdjustment::class   => StockAdjustmentPolicy::class,
            StockAlert::class        => StockAlertPolicy::class,
            Supplier::class          => SupplierPolicy::class,
            User::class              => UserPolicy::class,
            Worker::class            => WorkerPolicy::class,
            WorkerAttendance::class  => WorkerAttendancePolicy::class,
            WorkerSalary::class      => WorkerSalaryPolicy::class,
        ];

        foreach ($policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
