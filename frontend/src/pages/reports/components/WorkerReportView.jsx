import { Table } from '@/components/ui/Table'
import { formatCurrency } from '@/utils/formatCurrency'
import { toPaisas } from '@/utils/formatCurrency'

export function WorkerReportView({ data, loading }) {
  const rows = data?.rows || []

  const columns = [
    { key: 'worker_name',    title: 'Worker' },
    { key: 'pay_type',       title: 'Pay Type' },
    { key: 'days_present',   title: 'Days Present', render: (v) => v ?? 0 },
    { key: 'days_absent',    title: 'Days Absent',  render: (v) => v ?? 0 },
    { key: 'rate',           title: 'Rate/Day',     render: (v) => formatCurrency(toPaisas(v)) },
    { key: 'total_paid',     title: 'Total Paid',   render: (v) => <span className="font-semibold">{formatCurrency(toPaisas(v))}</span> },
  ]

  return <Table columns={columns} data={rows} loading={loading} />
}
