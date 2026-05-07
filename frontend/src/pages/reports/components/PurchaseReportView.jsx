import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function PurchaseReportView({ data, loading }) {
  const rows   = data?.rows   || []
  const totals = data?.totals || {}

  const columns = [
    { key: 'date',         title: 'Date',      render: (v) => formatDate(v) },
    { key: 'reference',    title: 'Ref #' },
    { key: 'party_name',   title: 'Supplier' },
    { key: 'total_paisas', title: 'Total',      render: (v) => formatCurrency(v) },
    { key: 'status',       title: 'Status' },
  ]

  return (
    <div>
      <Table columns={columns} data={rows} loading={loading} />
      {!loading && rows.length > 0 && (
        <div className="mt-3 flex justify-end border-t border-gray-200 pt-3">
          <div className="text-sm font-bold">
            Grand Total: <span className="text-blue-700 text-base">{formatCurrency(totals.grand_total)}</span>
          </div>
        </div>
      )}
    </div>
  )
}
