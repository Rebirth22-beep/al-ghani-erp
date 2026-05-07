import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function CashFlowTable({ data, loading }) {
  const columns = [
    { key: 'date',        title: 'Date',        render: (v) => formatDate(v) },
    { key: 'description', title: 'Description' },
    { key: 'in',          title: 'In',          render: (v) => v ? <span className="text-green-700 font-medium">{formatCurrency(v)}</span> : '—' },
    { key: 'out',         title: 'Out',         render: (v) => v ? <span className="text-red-600 font-medium">{formatCurrency(v)}</span> : '—' },
    { key: 'balance',     title: 'Balance',     render: (v) => <span className="font-semibold">{formatCurrency(v)}</span> },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
