import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'

export function TrialBalanceTable({ data, loading }) {
  const columns = [
    { key: 'account', title: 'Account' },
    { key: 'debit',   title: 'Debit',  render: (v) => v ? <span className="text-red-600 font-medium">{formatCurrency(v)}</span> : '—' },
    { key: 'credit',  title: 'Credit', render: (v) => v ? <span className="text-green-700 font-medium">{formatCurrency(v)}</span> : '—' },
  ]

  const rows     = data?.rows     || []
  const totals   = data?.totals   || {}

  return (
    <div>
      <Table columns={columns} data={rows} loading={loading} />
      {!loading && rows.length > 0 && (
        <div className="mt-2 flex justify-end border-t border-gray-200 pt-2">
          <div className="grid grid-cols-2 gap-8 text-sm font-semibold">
            <div>Total Debit: <span className="text-red-600">{formatCurrency(totals.total_debit)}</span></div>
            <div>Total Credit: <span className="text-green-700">{formatCurrency(totals.total_credit)}</span></div>
          </div>
        </div>
      )}
    </div>
  )
}
