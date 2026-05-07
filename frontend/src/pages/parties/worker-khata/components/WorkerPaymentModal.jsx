import { useEffect, useState } from 'react'
import { Modal } from '@/components/ui/Modal'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { format } from 'date-fns'

export function WorkerPaymentModal({ open, onClose, worker, onPay, isPending }) {
  const [month, setMonth]   = useState(format(new Date(), 'yyyy-MM'))
  const [amount, setAmount] = useState('')
  const [notes, setNotes]   = useState('')

  useEffect(() => {
    if (worker) setAmount(String(worker.rate || ''))
  }, [worker])

  const handleClose = () => {
    setNotes('')
    onClose()
  }

  const handlePay = () => {
    onPay({
      worker_id: worker.id,
      month,
      amount: parseFloat(amount),
      notes,
    })
  }

  if (!worker) return null

  return (
    <Modal open={open} onClose={handleClose} title={`Pay Salary — ${worker.name}`}>
      <div className="space-y-4">
        <div className="bg-gray-50 rounded-lg p-3">
          <p className="text-sm text-gray-500">Worker</p>
          <p className="font-semibold">{worker.name}</p>
        </div>
        <Input
          label="Month"
          type="month"
          value={month}
          onChange={(e) => setMonth(e.target.value)}
        />
        <Input
          label="Amount (Rs)"
          type="number"
          step="0.01"
          value={amount}
          onChange={(e) => setAmount(e.target.value)}
        />
        <Input
          label="Notes"
          placeholder="Optional notes"
          value={notes}
          onChange={(e) => setNotes(e.target.value)}
        />
        <div className="flex gap-3 justify-end">
          <Button variant="secondary" onClick={handleClose}>Cancel</Button>
          <Button onClick={handlePay} loading={isPending}>Pay Salary</Button>
        </div>
      </div>
    </Modal>
  )
}
