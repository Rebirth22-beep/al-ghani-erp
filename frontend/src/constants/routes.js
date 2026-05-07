export const ROUTES = {
  LOGIN:                '/login',
  FORGOT_PASSWORD:      '/forgot-password',
  DASHBOARD:            '/dashboard',

  // Trading
  SALES_INVOICES:       '/trading/sales-invoices',
  SALES_INVOICE_NEW:    '/trading/sales-invoices/new',
  SALES_INVOICE_EDIT:   '/trading/sales-invoices/:id/edit',
  PURCHASE_ENTRY:       '/trading/purchase-entry',
  PURCHASE_ENTRY_NEW:   '/trading/purchase-entry/new',
  PURCHASE_ENTRY_EDIT:  '/trading/purchase-entry/:id/edit',
  SALES_RETURNS:        '/trading/sales-returns',
  SALES_RETURN_NEW:     '/trading/sales-returns/new',
  PURCHASE_RETURNS:     '/trading/purchase-returns',
  PURCHASE_RETURN_NEW:  '/trading/purchase-returns/new',

  // Parties
  CUSTOMERS:            '/parties/customers',
  CUSTOMER_NEW:         '/parties/customers/new',
  CUSTOMER_EDIT:        '/parties/customers/:id/edit',
  CUSTOMER_LEDGER:      '/parties/customers/:id/ledger',
  SUPPLIERS:            '/parties/suppliers',
  SUPPLIER_NEW:         '/parties/suppliers/new',
  SUPPLIER_EDIT:        '/parties/suppliers/:id/edit',
  SUPPLIER_LEDGER:      '/parties/suppliers/:id/ledger',
  PARTY_LEDGER:         '/parties/ledger',
  WORKER_KHATA:         '/parties/workers',
  PARTNERS:             '/parties/partners',

  // Stock
  PRODUCTS:             '/stock/products',
  PRODUCT_NEW:          '/stock/products/new',
  PRODUCT_EDIT:         '/stock/products/:id/edit',
  BATCH_TRACKING:       '/stock/batches',
  STOCK_ALERTS:         '/stock/alerts',
  STOCK_ADJUSTMENTS:    '/stock/adjustments',
  STOCK_ADJUSTMENT_NEW: '/stock/adjustments/new',

  // Accounts
  ACCOUNTS:             '/accounts',
  GENERAL_LEDGER:       '/accounts/general-ledger',
  TRIAL_BALANCE:        '/accounts/trial-balance',
  PROFIT_LOSS:          '/accounts/profit-loss',
  BALANCE_SHEET:        '/accounts/balance-sheet',
  CASH_FLOW:            '/accounts/cash-flow',

  // Reports & Settings
  REPORTS:              '/reports',
  SETTINGS:             '/settings',
}
