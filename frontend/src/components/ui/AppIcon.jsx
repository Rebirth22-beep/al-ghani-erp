import {
  AlertTriangle,
  ChartColumn,
  CircleDollarSign,
  Clock3,
  LayoutDashboard,
  LogOut,
  Menu,
  Package,
  Plus,
  ReceiptText,
  Settings,
  TrendingUp,
  Users,
  X,
} from 'lucide-react'

const ICONS = {
  accounts:  CircleDollarSign,
  alert:     AlertTriangle,
  chart:     ChartColumn,
  clock:     Clock3,
  close:     X,
  customers: Users,
  dashboard: LayoutDashboard,
  delete:    X,
  logout:    LogOut,
  menu:      Menu,
  plus:      Plus,
  reports:   TrendingUp,
  sales:     ReceiptText,
  settings:  Settings,
  stock:     Package,
  trading:   ReceiptText,
}

/**
 * Central icon component.
 *
 * Use this instead of emojis or inline SVGs. Icons come from lucide-react
 * (free SVG icons), while Motion animations stay in shared wrappers/buttons.
 */
export function AppIcon({ name, size = 18, strokeWidth = 2, className = '', ...props }) {
  const Icon = ICONS[name] || AlertTriangle

  return (
    <Icon
      aria-hidden="true"
      className={className}
      size={size}
      strokeWidth={strokeWidth}
      {...props}
    />
  )
}
