import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function LedgerTable({ data, loading }) {
  const columns = [
    { key: 'date',        title: 'Date',        render: (v) => formatDate(v) },
    { key: 'description', title: 'Description' },
    { key: 'account',     title: 'Account' },
    { key: 'debit',       title: 'Debit',       render: (v) => v ? <span className="text-red-600 font-medium">{formatCurrency(v)}</span> : '—' },
    { key: 'credit',      title: 'Credit',      render: (v) => v ? <span className="text-green-700 font-medium">{formatCurrency(v)}</span> : '—' },
    { key: 'balance',     title: 'Balance',     render: (v) => <span className="font-semibold">{formatCurrency(v)}</span> },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
