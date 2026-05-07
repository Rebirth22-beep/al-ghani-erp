import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { formatCurrency } from '@/utils/formatCurrency'

export function PartyReportView({ data, loading }) {
  const rows = data?.rows || []

  const columns = [
    { key: 'party_name',     title: 'Party' },
    { key: 'type',           title: 'Type',    render: (v) => <Badge variant={v === 'customer' ? 'info' : 'warning'}>{v}</Badge> },
    { key: 'total_sales',    title: 'Sales',   render: (v) => formatCurrency(v) },
    { key: 'total_purchase', title: 'Purchase', render: (v) => formatCurrency(v) },
    { key: 'balance_paisas', title: 'Balance', render: (v) => <span className={`font-semibold ${v < 0 ? 'text-red-600' : 'text-green-700'}`}>{formatCurrency(v)}</span> },
  ]

  return <Table columns={columns} data={rows} loading={loading} />
}
