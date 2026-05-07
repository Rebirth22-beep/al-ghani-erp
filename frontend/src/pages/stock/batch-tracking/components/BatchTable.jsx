import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { formatDate } from '@/utils/formatDate'

const STATUS_VARIANT = {
  active:  'success',
  expired: 'danger',
  low:     'warning',
}

export function BatchTable({ data, loading }) {
  const columns = [
    { key: 'batch_number',    title: 'Batch #' },
    { key: 'product_name',    title: 'Product' },
    { key: 'qty',             title: 'Qty',              render: (v) => v ?? 0 },
    { key: 'manufacture_date', title: 'Manufacture Date', render: (v) => formatDate(v) },
    { key: 'expiry_date',     title: 'Expiry Date',      render: (v) => formatDate(v) },
    {
      key: 'status',
      title: 'Status',
      render: (v) => <Badge variant={STATUS_VARIANT[v] || 'info'}>{v}</Badge>,
    },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
