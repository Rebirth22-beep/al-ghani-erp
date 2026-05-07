import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { AuthGuard } from '@/components/guards/AuthGuard'
import { RoleGuard } from '@/components/guards/RoleGuard'
import { AppShell } from '@/components/layout/AppShell'
import { ROUTES } from '@/constants/routes'
import { ROLES } from '@/constants/roles'

// Auth pages
import { LoginPage }          from '@/pages/auth/LoginPage'
import { ForgotPasswordPage } from '@/pages/auth/ForgotPasswordPage'

// Dashboard
import { DashboardPage } from '@/pages/dashboard/DashboardPage'

// Trading
import { SalesInvoicePage }      from '@/pages/trading/sales-invoices/SalesInvoicePage'
import { SalesInvoiceFormPage }  from '@/pages/trading/sales-invoices/SalesInvoiceFormPage'
import { PurchaseEntryPage }     from '@/pages/trading/purchase-entry/PurchaseEntryPage'
import { PurchaseEntryFormPage } from '@/pages/trading/purchase-entry/PurchaseEntryFormPage'
import { SalesReturnPage }       from '@/pages/trading/sales-returns/SalesReturnPage'
import { SalesReturnFormPage }   from '@/pages/trading/sales-returns/SalesReturnFormPage'
import { PurchaseReturnPage }    from '@/pages/trading/purchase-returns/PurchaseReturnPage'
import { PurchaseReturnFormPage }from '@/pages/trading/purchase-returns/PurchaseReturnFormPage'

// Parties
import { CustomerListPage }   from '@/pages/parties/customers/CustomerListPage'
import { CustomerFormPage }   from '@/pages/parties/customers/CustomerFormPage'
import { CustomerLedgerPage } from '@/pages/parties/customers/CustomerLedgerPage'
import { SupplierListPage }   from '@/pages/parties/suppliers/SupplierListPage'
import { SupplierFormPage }   from '@/pages/parties/suppliers/SupplierFormPage'
import { SupplierLedgerPage } from '@/pages/parties/suppliers/SupplierLedgerPage'
import { PartyLedgerPage }    from '@/pages/parties/party-ledger/PartyLedgerPage'
import { WorkerKhataPage }    from '@/pages/parties/worker-khata/WorkerKhataPage'
import { PartnerListPage }    from '@/pages/parties/partners/PartnerListPage'

// Stock
import { ProductRegisterPage }    from '@/pages/stock/product-register/ProductRegisterPage'
import { ProductFormPage }        from '@/pages/stock/product-register/ProductFormPage'
import { BatchTrackingPage }      from '@/pages/stock/batch-tracking/BatchTrackingPage'
import { StockAlertPage }         from '@/pages/stock/stock-alerts/StockAlertPage'
import { StockAdjustmentPage }    from '@/pages/stock/stock-adjustments/StockAdjustmentPage'
import { StockAdjustmentFormPage }from '@/pages/stock/stock-adjustments/StockAdjustmentFormPage'

// Accounts
import { GeneralLedgerPage } from '@/pages/accounts/general-ledger/GeneralLedgerPage'
import { TrialBalancePage }  from '@/pages/accounts/trial-balance/TrialBalancePage'
import { ProfitLossPage }    from '@/pages/accounts/profit-loss/ProfitLossPage'
import { BalanceSheetPage }  from '@/pages/accounts/balance-sheet/BalanceSheetPage'
import { CashFlowPage }      from '@/pages/accounts/cash-flow/CashFlowPage'

// Reports & Settings
import { ReportsPage }  from '@/pages/reports/ReportsPage'
import { SettingsPage } from '@/pages/settings/SettingsPage'

const TRADING_ROLES   = [ROLES.ADMIN, ROLES.SALESMAN]
const ACCOUNTS_ROLES  = [ROLES.ADMIN, ROLES.ACCOUNTANT]
const ALL_ROLES       = [ROLES.ADMIN, ROLES.SALESMAN, ROLES.ACCOUNTANT]
const ADMIN_ONLY      = [ROLES.ADMIN]

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Public */}
        <Route path={ROUTES.LOGIN}           element={<LoginPage />} />
        <Route path={ROUTES.FORGOT_PASSWORD} element={<ForgotPasswordPage />} />

        {/* Protected */}
        <Route
          path="/"
          element={
            <AuthGuard>
              <AppShell />
            </AuthGuard>
          }
        >
          <Route index element={<Navigate to={ROUTES.DASHBOARD} replace />} />
          <Route path={ROUTES.DASHBOARD} element={<DashboardPage />} />

          {/* Trading */}
          <Route path={ROUTES.SALES_INVOICES}      element={<RoleGuard roles={TRADING_ROLES}><SalesInvoicePage /></RoleGuard>} />
          <Route path={ROUTES.SALES_INVOICE_NEW}   element={<RoleGuard roles={TRADING_ROLES}><SalesInvoiceFormPage /></RoleGuard>} />
          <Route path={ROUTES.SALES_INVOICE_EDIT}  element={<RoleGuard roles={TRADING_ROLES}><SalesInvoiceFormPage /></RoleGuard>} />
          <Route path={ROUTES.PURCHASE_ENTRY}      element={<RoleGuard roles={TRADING_ROLES}><PurchaseEntryPage /></RoleGuard>} />
          <Route path={ROUTES.PURCHASE_ENTRY_NEW}  element={<RoleGuard roles={TRADING_ROLES}><PurchaseEntryFormPage /></RoleGuard>} />
          <Route path={ROUTES.PURCHASE_ENTRY_EDIT} element={<RoleGuard roles={TRADING_ROLES}><PurchaseEntryFormPage /></RoleGuard>} />
          <Route path={ROUTES.SALES_RETURNS}       element={<RoleGuard roles={TRADING_ROLES}><SalesReturnPage /></RoleGuard>} />
          <Route path={ROUTES.SALES_RETURN_NEW}    element={<RoleGuard roles={TRADING_ROLES}><SalesReturnFormPage /></RoleGuard>} />
          <Route path={ROUTES.PURCHASE_RETURNS}    element={<RoleGuard roles={TRADING_ROLES}><PurchaseReturnPage /></RoleGuard>} />
          <Route path={ROUTES.PURCHASE_RETURN_NEW} element={<RoleGuard roles={TRADING_ROLES}><PurchaseReturnFormPage /></RoleGuard>} />

          {/* Parties */}
          <Route path={ROUTES.CUSTOMERS}       element={<RoleGuard roles={ALL_ROLES}><CustomerListPage /></RoleGuard>} />
          <Route path={ROUTES.CUSTOMER_NEW}    element={<RoleGuard roles={ALL_ROLES}><CustomerFormPage /></RoleGuard>} />
          <Route path={ROUTES.CUSTOMER_EDIT}   element={<RoleGuard roles={ALL_ROLES}><CustomerFormPage /></RoleGuard>} />
          <Route path={ROUTES.CUSTOMER_LEDGER} element={<RoleGuard roles={ALL_ROLES}><CustomerLedgerPage /></RoleGuard>} />
          <Route path={ROUTES.SUPPLIERS}       element={<RoleGuard roles={ALL_ROLES}><SupplierListPage /></RoleGuard>} />
          <Route path={ROUTES.SUPPLIER_NEW}    element={<RoleGuard roles={ALL_ROLES}><SupplierFormPage /></RoleGuard>} />
          <Route path={ROUTES.SUPPLIER_EDIT}   element={<RoleGuard roles={ALL_ROLES}><SupplierFormPage /></RoleGuard>} />
          <Route path={ROUTES.SUPPLIER_LEDGER} element={<RoleGuard roles={ALL_ROLES}><SupplierLedgerPage /></RoleGuard>} />
          <Route path={ROUTES.PARTY_LEDGER}    element={<RoleGuard roles={ACCOUNTS_ROLES}><PartyLedgerPage /></RoleGuard>} />
          <Route path={ROUTES.WORKER_KHATA}    element={<RoleGuard roles={ALL_ROLES}><WorkerKhataPage /></RoleGuard>} />
          <Route path={ROUTES.PARTNERS}        element={<RoleGuard roles={ACCOUNTS_ROLES}><PartnerListPage /></RoleGuard>} />

          {/* Stock */}
          <Route path={ROUTES.PRODUCTS}             element={<RoleGuard roles={TRADING_ROLES}><ProductRegisterPage /></RoleGuard>} />
          <Route path={ROUTES.PRODUCT_NEW}          element={<RoleGuard roles={TRADING_ROLES}><ProductFormPage /></RoleGuard>} />
          <Route path={ROUTES.PRODUCT_EDIT}         element={<RoleGuard roles={TRADING_ROLES}><ProductFormPage /></RoleGuard>} />
          <Route path={ROUTES.BATCH_TRACKING}       element={<RoleGuard roles={TRADING_ROLES}><BatchTrackingPage /></RoleGuard>} />
          <Route path={ROUTES.STOCK_ALERTS}         element={<RoleGuard roles={TRADING_ROLES}><StockAlertPage /></RoleGuard>} />
          <Route path={ROUTES.STOCK_ADJUSTMENTS}    element={<RoleGuard roles={TRADING_ROLES}><StockAdjustmentPage /></RoleGuard>} />
          <Route path={ROUTES.STOCK_ADJUSTMENT_NEW} element={<RoleGuard roles={TRADING_ROLES}><StockAdjustmentFormPage /></RoleGuard>} />

          {/* Accounts */}
          <Route path={ROUTES.GENERAL_LEDGER} element={<RoleGuard roles={ACCOUNTS_ROLES}><GeneralLedgerPage /></RoleGuard>} />
          <Route path={ROUTES.TRIAL_BALANCE}  element={<RoleGuard roles={ACCOUNTS_ROLES}><TrialBalancePage /></RoleGuard>} />
          <Route path={ROUTES.PROFIT_LOSS}    element={<RoleGuard roles={ACCOUNTS_ROLES}><ProfitLossPage /></RoleGuard>} />
          <Route path={ROUTES.BALANCE_SHEET}  element={<RoleGuard roles={ACCOUNTS_ROLES}><BalanceSheetPage /></RoleGuard>} />
          <Route path={ROUTES.CASH_FLOW}      element={<RoleGuard roles={ACCOUNTS_ROLES}><CashFlowPage /></RoleGuard>} />

          {/* Reports & Settings */}
          <Route path={ROUTES.REPORTS}  element={<RoleGuard roles={ACCOUNTS_ROLES}><ReportsPage /></RoleGuard>} />
          <Route path={ROUTES.SETTINGS} element={<RoleGuard roles={ADMIN_ONLY}><SettingsPage /></RoleGuard>} />
        </Route>

        <Route path="*" element={<Navigate to={ROUTES.DASHBOARD} replace />} />
      </Routes>
    </BrowserRouter>
  )
}
