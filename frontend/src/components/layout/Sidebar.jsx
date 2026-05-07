import { NavLink } from 'react-router-dom'
import { AnimatePresence, motion } from 'motion/react'
import { useSettingsStore } from '@/stores/settingsStore'
import { usePermission } from '@/hooks/usePermission'
import { ROUTES } from '@/constants/routes'
import { ROLES } from '@/constants/roles'
import { AppIcon } from '@/components/ui/AppIcon'

const NAV_ITEMS = [
  {
    label: 'Dashboard',
    icon:  'dashboard',
    to:    ROUTES.DASHBOARD,
    roles: [ROLES.ADMIN, ROLES.SALESMAN, ROLES.ACCOUNTANT],
  },
  {
    label: 'Trading',
    icon:  'trading',
    roles: [ROLES.ADMIN, ROLES.SALESMAN],
    children: [
      { label: 'Sales Invoices',    to: ROUTES.SALES_INVOICES },
      { label: 'Purchase Entry',    to: ROUTES.PURCHASE_ENTRY },
      { label: 'Sales Returns',     to: ROUTES.SALES_RETURNS },
      { label: 'Purchase Returns',  to: ROUTES.PURCHASE_RETURNS },
    ],
  },
  {
    label: 'Parties',
    icon:  'customers',
    roles: [ROLES.ADMIN, ROLES.SALESMAN, ROLES.ACCOUNTANT],
    children: [
      { label: 'Customers',    to: ROUTES.CUSTOMERS },
      { label: 'Suppliers',    to: ROUTES.SUPPLIERS },
      { label: 'Party Ledger', to: ROUTES.PARTY_LEDGER },
      { label: 'Worker Khata', to: ROUTES.WORKER_KHATA },
      { label: 'Partners',     to: ROUTES.PARTNERS },
    ],
  },
  {
    label: 'Stock',
    icon:  'stock',
    roles: [ROLES.ADMIN, ROLES.SALESMAN],
    children: [
      { label: 'Products',          to: ROUTES.PRODUCTS },
      { label: 'Batch Tracking',    to: ROUTES.BATCH_TRACKING },
      { label: 'Stock Alerts',      to: ROUTES.STOCK_ALERTS },
      { label: 'Stock Adjustments', to: ROUTES.STOCK_ADJUSTMENTS },
    ],
  },
  {
    label: 'Accounts',
    icon:  'accounts',
    roles: [ROLES.ADMIN, ROLES.ACCOUNTANT],
    children: [
      { label: 'General Ledger', to: ROUTES.GENERAL_LEDGER },
      { label: 'Trial Balance',  to: ROUTES.TRIAL_BALANCE },
      { label: 'Profit & Loss',  to: ROUTES.PROFIT_LOSS },
      { label: 'Balance Sheet',  to: ROUTES.BALANCE_SHEET },
      { label: 'Cash Flow',      to: ROUTES.CASH_FLOW },
    ],
  },
  {
    label: 'Reports',
    icon:  'reports',
    to:    ROUTES.REPORTS,
    roles: [ROLES.ADMIN, ROLES.ACCOUNTANT],
  },
  {
    label: 'Settings',
    icon:  'settings',
    to:    ROUTES.SETTINGS,
    roles: [ROLES.ADMIN],
  },
]

export function Sidebar() {
  const sidebarOpen = useSettingsStore((s) => s.sidebarOpen)
  const { can }     = usePermission()

  return (
    <motion.aside
      className="fixed top-0 left-0 h-full bg-white border-r border-gray-200 shadow-sm z-40 overflow-hidden"
      animate={{ width: sidebarOpen ? 256 : 64 }}
      initial={false}
      transition={{ type: 'spring', stiffness: 360, damping: 36 }}
    >
      {/* Logo */}
      <div className="h-16 flex items-center px-4 border-b border-gray-200">
        <div className="w-8 h-8 bg-brand rounded-lg flex items-center justify-center text-white font-bold text-sm shrink-0">
          AG
        </div>
        <AnimatePresence initial={false}>
          {sidebarOpen && (
            <motion.span
              className="ml-3 text-sm font-semibold text-gray-900 truncate"
              initial={{ opacity: 0, x: -6 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -6 }}
            >
              Al-Ghani ERP
            </motion.span>
          )}
        </AnimatePresence>
      </div>

      {/* Nav */}
      <nav className="p-2 space-y-0.5 overflow-y-auto h-[calc(100vh-4rem)]">
        {NAV_ITEMS.filter((item) => can(item.roles)).map((item) =>
          item.to ? (
            <NavLink
              key={item.to}
              to={item.to}
              className={({ isActive }) =>
                `flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors ${
                  isActive ? 'bg-brand/10 text-brand font-medium' : 'text-gray-600 hover:bg-gray-100'
                }`
              }
            >
              <AppIcon name={item.icon} className="shrink-0" />
              {sidebarOpen && <span>{item.label}</span>}
            </NavLink>
          ) : (
            <SidebarGroup key={item.label} item={item} sidebarOpen={sidebarOpen} />
          )
        )}
      </nav>
    </motion.aside>
  )
}

function SidebarGroup({ item, sidebarOpen }) {
  return (
    <div>
      <div className={`flex items-center gap-3 px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider ${sidebarOpen ? '' : 'justify-center'}`}>
        <AppIcon name={item.icon} className="shrink-0" />
        {sidebarOpen && <span>{item.label}</span>}
      </div>
      <AnimatePresence initial={false}>
        {sidebarOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="overflow-hidden"
          >
            {item.children?.map((child) => (
              <NavLink
                key={child.to}
                to={child.to}
                className={({ isActive }) =>
                  `flex items-center gap-3 pl-9 pr-3 py-1.5 rounded-lg text-sm transition-colors ${
                    isActive ? 'bg-brand/10 text-brand font-medium' : 'text-gray-600 hover:bg-gray-100'
                  }`
                }
              >
                {child.label}
              </NavLink>
            ))}
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  )
}
