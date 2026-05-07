import { Link } from 'react-router-dom'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'
import { Badge } from '@/components/ui/Badge'
import { AnimatedList } from '@/components/ui/AnimatedList'
import { ROUTES } from '@/constants/routes'

const STATUS_VARIANT = { draft: 'warning', posted: 'success', cancelled: 'danger' }

export function RecentBillsWidget({ bills = [] }) {
  if (!bills.length) {
    return <p className="text-sm text-gray-400">No recent bills.</p>
  }

  return (
    <AnimatedList
      items={bills}
      keyExtractor={(bill) => bill.id}
      className="divide-y divide-gray-100"
      renderItem={(bill) => (
        <div className="py-2.5 flex items-center justify-between">
          <div>
            <Link
              to={`${ROUTES.SALES_INVOICES}/${bill.id}`}
              className="text-sm font-medium text-brand hover:underline"
            >
              {bill.bill_number}
            </Link>
            <p className="text-xs text-gray-400">{bill.party_name} · {formatDate(bill.date)}</p>
          </div>
          <div className="flex items-center gap-3">
            <span className="text-sm font-medium">{formatCurrency(bill.total_paisas)}</span>
            <Badge variant={STATUS_VARIANT[bill.status] || 'default'}>{bill.status}</Badge>
          </div>
        </div>
      )}
    />
  )
}
