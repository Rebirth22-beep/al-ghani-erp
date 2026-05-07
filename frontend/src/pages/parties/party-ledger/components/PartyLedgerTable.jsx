import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function PartyLedgerTable({ data, loading }) {
  const columns = [
    { key: 'date',        title: 'Date',    render: (v) => formatDate(v) },
    { key: 'party_name',  title: 'Party' },
    { key: 'type',        title: 'Type',    render: (v) => <Badge variant={v === 'customer' ? 'info' : 'warning'}>{v}</Badge> },
    { key: 'debit',       title: 'Debit',   render: (v) => v ? <span className="text-red-600 font-medium">{formatCurrency(v)}</span> : '—' },
    { key: 'credit',      title: 'Credit',  render: (v) => v ? <span className="text-green-700 font-medium">{formatCurrency(v)}</span> : '—' },
    { key: 'balance',     title: 'Balance', render: (v) => <span className="font-semibold">{formatCurrency(v)}</span> },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
