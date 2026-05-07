import { Link } from 'react-router-dom'
import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'

export function PartnerTable({ data, loading, onDelete }) {
  const columns = [
    { key: 'name',           title: 'Name' },
    { key: 'phone',          title: 'Phone',    render: (v) => v || '—' },
    { key: 'share_percent',  title: 'Share %',  render: (v) => v != null ? `${v}%` : '—' },
    { key: 'balance_paisas', title: 'Balance',  render: (v) => <span className="font-medium">{formatCurrency(v)}</span> },
    {
      key: 'id',
      title: 'Actions',
      render: (id) => (
        <div className="flex items-center gap-2">
          <Link to={`/parties/partners/${id}/edit`} className="text-xs text-brand hover:underline">Edit</Link>
          <button onClick={() => onDelete(id)} className="text-xs text-red-500 hover:underline">Delete</button>
        </div>
      ),
    },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
