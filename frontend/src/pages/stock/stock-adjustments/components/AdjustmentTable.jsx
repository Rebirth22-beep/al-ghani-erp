import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { formatDate } from '@/utils/formatDate'

export function AdjustmentTable({ data, loading, onDelete }) {
  const columns = [
    { key: 'date',         title: 'Date',     render: (v) => formatDate(v) },
    { key: 'product_name', title: 'Product' },
    {
      key: 'type',
      title: 'Type',
      render: (v) => <Badge variant={v === 'increase' ? 'success' : 'danger'}>{v}</Badge>,
    },
    { key: 'qty',         title: 'Qty',      render: (v) => v ?? 0 },
    { key: 'reason',      title: 'Reason' },
    { key: 'done_by',     title: 'Done By',  render: (v) => v || '—' },
    {
      key: 'id',
      title: 'Actions',
      render: (id) => (
        <div className="flex items-center gap-2">
          <button onClick={() => onDelete(id)} className="text-xs text-red-500 hover:underline">Delete</button>
        </div>
      ),
    },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
