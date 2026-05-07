import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'

export function StockReportView({ data, loading }) {
  const rows = data?.rows || []

  const columns = [
    { key: 'product_name',     title: 'Product' },
    { key: 'category',         title: 'Category',      render: (v) => v || '—' },
    { key: 'unit',             title: 'Unit' },
    { key: 'opening_qty',      title: 'Opening Qty',   render: (v) => v ?? 0 },
    { key: 'in_qty',           title: 'In',            render: (v) => v ?? 0 },
    { key: 'out_qty',          title: 'Out',           render: (v) => v ?? 0 },
    { key: 'closing_qty',      title: 'Closing Qty',   render: (v) => <span className="font-semibold">{v ?? 0}</span> },
    { key: 'sale_rate_paisas', title: 'Sale Rate',     render: (v) => formatCurrency(v) },
  ]

  return <Table columns={columns} data={rows} loading={loading} />
}
