import { Link } from 'react-router-dom'
import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'

export function SupplierTable({ data, loading, onDelete }) {
  const columns = [
    { key: 'name',           title: 'Name' },
    { key: 'phone',          title: 'Phone',   render: (v) => v || '—' },
    { key: 'city',           title: 'City',    render: (v) => v || '—' },
    { key: 'balance_paisas', title: 'Balance', render: (v) => <span className={`font-medium ${v < 0 ? 'text-red-600' : 'text-green-700'}`}>{formatCurrency(v)}</span> },
    {
      key: 'id',
      title: 'Actions',
      render: (id) => (
        <div className="flex items-center gap-2">
          <Link to={`/parties/suppliers/${id}/edit`} className="text-xs text-brand hover:underline">Edit</Link>
          <Link to={`/parties/suppliers/${id}/ledger`} className="text-xs text-indigo-600 hover:underline">Ledger</Link>
          <button onClick={() => onDelete(id)} className="text-xs text-red-500 hover:underline">Delete</button>
        </div>
      ),
    },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
