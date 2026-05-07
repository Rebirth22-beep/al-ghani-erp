import { Link } from 'react-router-dom'
import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'

export function ProductTable({ data, loading, onDelete }) {
  const columns = [
    { key: 'name',             title: 'Name' },
    { key: 'category',         title: 'Category',  render: (v) => v || '—' },
    { key: 'unit',             title: 'Unit' },
    { key: 'pack_size',        title: 'Pack Size',  render: (v) => v || '—' },
    { key: 'sale_rate_paisas', title: 'Sale Rate',  render: (v) => formatCurrency(v) },
    { key: 'stock_qty',        title: 'Stock Qty',  render: (v) => <span className={`font-medium ${v <= 0 ? 'text-red-600' : ''}`}>{v ?? 0}</span> },
    {
      key: 'id',
      title: 'Actions',
      render: (id) => (
        <div className="flex items-center gap-2">
          <Link to={`/stock/product-register/${id}/edit`} className="text-xs text-brand hover:underline">Edit</Link>
          <button onClick={() => onDelete(id)} className="text-xs text-red-500 hover:underline">Delete</button>
        </div>
      ),
    },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
