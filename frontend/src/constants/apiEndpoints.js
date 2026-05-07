/**
 * API Endpoints — Al-Ghani ERP
 *
 * NOTE: These paths are RELATIVE to the axios baseURL configured in
 * `src/config/axios.js` (which already includes `/api/v1`).
 *
 * Do NOT prefix paths here with `/api/v1` — that prefix lives in ONE
 * place only (the baseURL) per the DRY principle.
 */

export const API = {
  AUTH: {
    LOGIN:           '/auth/login',
    LOGOUT:          '/auth/logout',
    ME:              '/auth/me',
    FORGOT_PASSWORD: '/auth/forgot-password',
  },
  DASHBOARD: {
    STATS:        '/dashboard/stats',
    SALES_CHART:  '/dashboard/sales-chart',
    RECENT_BILLS: '/dashboard/recent-bills',
  },
  SALE_INVOICES:     '/sale-invoices',
  PURCHASE_ENTRIES:  '/purchase-entries',
  SALES_RETURNS:     '/sales-returns',
  PURCHASE_RETURNS:  '/purchase-returns',
  CUSTOMERS:         '/customers',
  SUPPLIERS:         '/suppliers',
  PARTY_LEDGER:      '/party-ledger',
  WORKERS:           '/workers',
  WORKER_ATTENDANCE: '/worker-attendance',
  WORKER_SALARY:     '/worker-salary',
  PARTNERS:          '/partners',
  PRODUCTS:          '/products',
  BATCHES:           '/batches',
  STOCK_ALERTS:      '/stock-alerts',
  STOCK_ADJUSTMENTS: '/stock-adjustments',
  GENERAL_LEDGER:    '/accounts/general-ledger',
  TRIAL_BALANCE:     '/accounts/trial-balance',
  PROFIT_LOSS:       '/accounts/profit-loss',
  BALANCE_SHEET:     '/accounts/balance-sheet',
  CASH_FLOW:         '/accounts/cash-flow',
  REPORTS:           '/reports',
  USERS:             '/users',
  SETTINGS:          '/settings',
}
