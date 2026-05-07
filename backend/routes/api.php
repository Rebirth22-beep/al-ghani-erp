<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\SaleInvoiceController;
use App\Http\Controllers\Api\V1\PurchaseEntryController;
use App\Http\Controllers\Api\V1\SalesReturnController;
use App\Http\Controllers\Api\V1\PurchaseReturnController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\SupplierController;
use App\Http\Controllers\Api\V1\PartyLedgerController;
use App\Http\Controllers\Api\V1\WorkerController;
use App\Http\Controllers\Api\V1\WorkerAttendanceController;
use App\Http\Controllers\Api\V1\WorkerSalaryController;
use App\Http\Controllers\Api\V1\PartnerController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\BatchController;
use App\Http\Controllers\Api\V1\StockAlertController;
use App\Http\Controllers\Api\V1\StockAdjustmentController;
use App\Http\Controllers\Api\V1\GeneralLedgerController;
use App\Http\Controllers\Api\V1\TrialBalanceController;
use App\Http\Controllers\Api\V1\ProfitLossController;
use App\Http\Controllers\Api\V1\BalanceSheetController;
use App\Http\Controllers\Api\V1\CashFlowController;
use App\Http\Controllers\Api\V1\JvController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\SettingController;

// Public routes
Route::prefix('v1')->group(function () {
    Route::post('auth/login',          [AuthController::class, 'login']);
    Route::post('auth/forgot-password',[AuthController::class, 'forgotPassword']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);

        Route::get('dashboard/stats',       [DashboardController::class, 'stats']);
        Route::get('dashboard/sales-chart', [DashboardController::class, 'salesChart']);
        Route::get('dashboard/recent-bills',[DashboardController::class, 'recentBills']);

        // Trading
        Route::apiResource('sale-invoices',    SaleInvoiceController::class);
        Route::post('sale-invoices/{saleInvoice}/post', [SaleInvoiceController::class, 'postInvoice']);
        Route::get('sale-invoices/party/{partyId}',     [SaleInvoiceController::class, 'partyWise']);

        Route::apiResource('purchase-entries', PurchaseEntryController::class);
        Route::post('purchase-entries/{purchaseEntry}/post', [PurchaseEntryController::class, 'postEntry']);

        Route::apiResource('sales-returns',    SalesReturnController::class);
        Route::apiResource('purchase-returns', PurchaseReturnController::class);

        // Parties
        Route::apiResource('customers', CustomerController::class);
        Route::get('customers/{customer}/ledger', [CustomerController::class, 'ledger']);

        Route::apiResource('suppliers', SupplierController::class);
        Route::get('suppliers/{supplier}/ledger', [SupplierController::class, 'ledger']);

        Route::get('party-ledger', [PartyLedgerController::class, 'index']);

        Route::apiResource('workers', WorkerController::class);
        Route::apiResource('worker-attendance', WorkerAttendanceController::class)->only(['index','store']);
        Route::apiResource('worker-salary',     WorkerSalaryController::class)->only(['index','store']);

        Route::apiResource('partners', PartnerController::class);
        Route::get('partners/{partner}/ledger', [PartnerController::class, 'ledger']);

        // Stock
        Route::apiResource('products',           ProductController::class);
        Route::apiResource('batches',            BatchController::class)->only(['index','show']);
        Route::apiResource('stock-alerts',       StockAlertController::class)->only(['index']);
        Route::apiResource('stock-adjustments',  StockAdjustmentController::class)->only(['index','store','show','destroy']);

        // Accounts
        Route::get('accounts/general-ledger', [GeneralLedgerController::class, 'index']);
        Route::get('accounts/trial-balance',  [TrialBalanceController::class, 'index']);
        Route::get('accounts/profit-loss',    [ProfitLossController::class, 'index']);
        Route::get('accounts/balance-sheet',  [BalanceSheetController::class, 'index']);
        Route::get('accounts/cash-flow',      [CashFlowController::class, 'index']);

        // Journal Vouchers (manual ledger entries — must balance DR == CR)
        Route::apiResource('jv', JvController::class)->only(['index','show','store','destroy']);

        // Reports
        Route::get('reports/sales',    [ReportController::class, 'sales']);
        Route::get('reports/purchase', [ReportController::class, 'purchase']);
        Route::get('reports/stock',    [ReportController::class, 'stock']);
        Route::get('reports/party',    [ReportController::class, 'party']);
        Route::get('reports/worker',   [ReportController::class, 'worker']);

        // Users & Settings (Admin only)
        Route::apiResource('users',    UserController::class);
        Route::get('settings',         [SettingController::class, 'index']);
        Route::put('settings',         [SettingController::class, 'update']);
    });
});
