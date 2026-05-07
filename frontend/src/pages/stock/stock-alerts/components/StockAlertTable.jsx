import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'

export function StockAlertTable({ data, loading }) {
  const getStatus = (current, min) => {
    if (current <= 0)       return { label: 'Critical', variant: 'danger' }
    if (current <= min)     return { label: 'Low',      variant: 'warning' }
    return                         { label: 'OK',       variant: 'success' }
  }

  const columns = [
    { key: 'product_name', title: 'Product' },
    { key: 'stock_qty',    title: 'Current Stock', render: (v) => <span className="font-medium">{v ?? 0}</span> },
    { key: 'min_stock',    title: 'Min Stock',     render: (v) => v ?? 0 },
    {
      key: 'stock_qty',
      title: 'Status',
      render: (v, row) => {
        const s = getStatus(v, row.min_stock)
        return <Badge variant={s.variant}>{s.label}</Badge>
      },
    },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
