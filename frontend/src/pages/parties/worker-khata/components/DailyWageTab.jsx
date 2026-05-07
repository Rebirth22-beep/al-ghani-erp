import { useState } from 'react'
import { format } from 'date-fns'
import { useWorkerList, useAttendanceList, useMarkAttendance } from '../hooks/useWorkerKhata'
import { WorkerAttendanceTable } from './WorkerAttendanceTable'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Badge } from '@/components/ui/Badge'
import { Modal } from '@/components/ui/Modal'
import { Input } from '@/components/ui/Input'
import { formatCurrency } from '@/utils/formatCurrency'
import { toPaisas } from '@/utils/formatCurrency'

export function DailyWageTab() {
  const currentMonth        = format(new Date(), 'yyyy-MM')
  const [modalOpen, setModalOpen]   = useState(false)
  const [selectedWorker, setSelectedWorker] = useState(null)
  const [attendanceDate, setAttendanceDate] = useState(format(new Date(), 'yyyy-MM-dd'))
  const [isPresent, setIsPresent]           = useState(true)
  const [notes, setNotes]                   = useState('')

  const { data: workersData } = useWorkerList({ pay_type: 'daily' })
  const { data: attendanceData, isLoading } = useAttendanceList({ month: currentMonth })
  const markAttendance = useMarkAttendance()

  const workers = workersData?.data || []

  const openModal = (worker) => {
    setSelectedWorker(worker)
    setModalOpen(true)
  }

  const handleMark = () => {
    markAttendance.mutate({
      worker_id:  selectedWorker.id,
      date:       attendanceDate,
      is_present: isPresent,
      notes,
    }, {
      onSuccess: () => {
        setModalOpen(false)
        setNotes('')
      },
    })
  }

  return (
    <div className="space-y-4">
      <Card title="Daily Workers">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr>
                <th className="table-th">Name</th>
                <th className="table-th">Phone</th>
                <th className="table-th">Rate/Day</th>
                <th className="table-th">Actions</th>
              </tr>
            </thead>
            <tbody>
              {workers.map((w) => (
                <tr key={w.id}>
                  <td className="table-td font-medium">{w.name}</td>
                  <td className="table-td">{w.phone || '—'}</td>
                  <td className="table-td">{formatCurrency(toPaisas(w.rate))}</td>
                  <td className="table-td">
                    <Button size="sm" variant="secondary" onClick={() => openModal(w)}>
                      Mark Attendance
                    </Button>
                  </td>
                </tr>
              ))}
              {workers.length === 0 && (
                <tr><td colSpan={4} className="table-td text-center text-gray-400">No daily workers found.</td></tr>
              )}
            </tbody>
          </table>
        </div>
      </Card>

      <Card title={`Attendance — ${currentMonth}`}>
        <WorkerAttendanceTable data={attendanceData?.data} loading={isLoading} />
      </Card>

      <Modal open={modalOpen} onClose={() => setModalOpen(false)} title={`Mark Attendance — ${selectedWorker?.name}`}>
        <div className="space-y-4">
          <Input
            label="Date"
            type="date"
            value={attendanceDate}
            onChange={(e) => setAttendanceDate(e.target.value)}
          />
          <div className="flex gap-3">
            <Button
              type="button"
              variant={isPresent ? 'primary' : 'secondary'}
              onClick={() => setIsPresent(true)}
            >
              Present
            </Button>
            <Button
              type="button"
              variant={!isPresent ? 'danger' : 'secondary'}
              onClick={() => setIsPresent(false)}
            >
              Absent
            </Button>
          </div>
          <Input
            label="Notes"
            placeholder="Optional notes"
            value={notes}
            onChange={(e) => setNotes(e.target.value)}
          />
          <div className="flex gap-3 justify-end">
            <Button variant="secondary" onClick={() => setModalOpen(false)}>Cancel</Button>
            <Button onClick={handleMark} loading={markAttendance.isPending}>Save</Button>
          </div>
        </div>
      </Modal>
    </div>
  )
}
