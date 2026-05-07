import { useState } from 'react'
import { useSettings, useUpdateSettings } from '../hooks/useSettings'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Input } from '@/components/ui/Input'
import { Modal } from '@/components/ui/Modal'
import { Badge } from '@/components/ui/Badge'
import { formatDate } from '@/utils/formatDate'

export function FiscalYearTab() {
  const { data: settings } = useSettings()
  const updateSettings     = useUpdateSettings()
  const [modalOpen, setModalOpen]   = useState(false)
  const [startDate, setStartDate]   = useState('')
  const [endDate, setEndDate]       = useState('')

  const fy = settings?.fiscal_year

  const handleOpen = () => {
    updateSettings.mutate({
      fiscal_year: { start_date: startDate, end_date: endDate, status: 'active' },
    }, { onSuccess: () => setModalOpen(false) })
  }

  return (
    <div className="max-w-lg space-y-4">
      {fy && (
        <Card title="Current Fiscal Year">
          <div className="space-y-2 text-sm">
            <div className="flex justify-between">
              <span className="text-gray-500">Start Date</span>
              <span className="font-medium">{formatDate(fy.start_date)}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-500">End Date</span>
              <span className="font-medium">{formatDate(fy.end_date)}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-500">Status</span>
              <Badge variant={fy.status === 'active' ? 'success' : 'warning'}>{fy.status}</Badge>
            </div>
          </div>
        </Card>
      )}

      <Button onClick={() => setModalOpen(true)}>Open New Fiscal Year</Button>

      <Modal open={modalOpen} onClose={() => setModalOpen(false)} title="Open New Fiscal Year">
        <div className="space-y-4">
          <Input
            label="Start Date"
            type="date"
            value={startDate}
            onChange={(e) => setStartDate(e.target.value)}
          />
          <Input
            label="End Date"
            type="date"
            value={endDate}
            onChange={(e) => setEndDate(e.target.value)}
          />
          <div className="flex gap-3 justify-end">
            <Button variant="secondary" onClick={() => setModalOpen(false)}>Cancel</Button>
            <Button onClick={handleOpen} loading={updateSettings.isPending}>Open Fiscal Year</Button>
          </div>
        </div>
      </Modal>
    </div>
  )
}
