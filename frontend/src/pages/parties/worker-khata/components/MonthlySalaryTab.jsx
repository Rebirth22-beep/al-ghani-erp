import { useState } from 'react'
import { format } from 'date-fns'
import { useWorkerList, useSalaryList, usePaySalary } from '../hooks/useWorkerKhata'
import { WorkerPaymentModal } from './WorkerPaymentModal'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Badge } from '@/components/ui/Badge'
import { formatCurrency } from '@/utils/formatCurrency'
import { toPaisas } from '@/utils/formatCurrency'

export function MonthlySalaryTab() {
  const currentMonth                    = format(new Date(), 'yyyy-MM')
  const [selectedWorker, setSelectedWorker] = useState(null)
  const [payModalOpen, setPayModalOpen]     = useState(false)

  const { data: workersData }  = useWorkerList({ pay_type: 'monthly' })
  const { data: salaryData }   = useSalaryList({ month: currentMonth })
  const paySalary              = usePaySalary()

  const workers    = workersData?.data || []
  const salaryPaid = salaryData?.data || []

  const isPaid = (workerId) => salaryPaid.some((s) => s.worker_id === workerId)

  const openPayModal = (worker) => {
    setSelectedWorker(worker)
    setPayModalOpen(true)
  }

  const handlePay = (payload) => {
    paySalary.mutate(payload, { onSuccess: () => setPayModalOpen(false) })
  }

  return (
    <div className="space-y-4">
      <Card title={`Monthly Workers — ${currentMonth}`}>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr>
                <th className="table-th">Name</th>
                <th className="table-th">Phone</th>
                <th className="table-th">Monthly Rate</th>
                <th className="table-th">Status</th>
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
                    <Badge variant={isPaid(w.id) ? 'success' : 'warning'}>
                      {isPaid(w.id) ? 'Paid' : 'Pending'}
                    </Badge>
                  </td>
                  <td className="table-td">
                    {!isPaid(w.id) && (
                      <Button size="sm" onClick={() => openPayModal(w)}>Pay</Button>
                    )}
                  </td>
                </tr>
              ))}
              {workers.length === 0 && (
                <tr><td colSpan={5} className="table-td text-center text-gray-400">No monthly workers found.</td></tr>
              )}
            </tbody>
          </table>
        </div>
      </Card>

      <WorkerPaymentModal
        open={payModalOpen}
        onClose={() => setPayModalOpen(false)}
        worker={selectedWorker}
        onPay={handlePay}
        isPending={paySalary.isPending}
      />
    </div>
  )
}
