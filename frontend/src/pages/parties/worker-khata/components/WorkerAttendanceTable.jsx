import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { formatDate } from '@/utils/formatDate'

export function WorkerAttendanceTable({ data, loading }) {
  const columns = [
    { key: 'worker_name', title: 'Worker' },
    { key: 'date',        title: 'Date',    render: (v) => formatDate(v) },
    {
      key: 'is_present',
      title: 'Status',
      render: (v) => <Badge variant={v ? 'success' : 'danger'}>{v ? 'Present' : 'Absent'}</Badge>,
    },
    { key: 'notes', title: 'Notes', render: (v) => v || '—' },
  ]

  return <Table columns={columns} data={data} loading={loading} />
}
