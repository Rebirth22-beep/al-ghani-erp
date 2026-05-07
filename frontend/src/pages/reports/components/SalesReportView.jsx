import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function SalesReportView({ data, loading }) {
  const rows   = data?.rows   || []
  const totals = data?.totals || {}

  const columns = [
    { key: 'date',         title: 'Date',        render: (v) => formatDate(v) },
    { key: 'bill_number',  title: 'Bill #' },
    { key: 'party_name',   title: 'Party' },
    { key: 'total_paisas', title: 'Total',        render: (v) => formatCurrency(v) },
    { key: 'payment_type', title: 'Payment Type' },
    { key: 'status',       title: 'Status' },
  ]

  return (
    <div>
      <Table columns={columns} data={rows} loading={loading} />
      {!loading && rows.length > 0 && (
        <div className="mt-3 flex justify-end border-t border-gray-200 pt-3">
          <div className="text-sm font-bold">
            Grand Total: <span className="text-green-700 text-base">{formatCurrency(totals.grand_total)}</span>
          </div>
        </div>
      )}
    </div>
  )
}
