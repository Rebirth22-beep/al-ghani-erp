import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Tabs } from '@/components/ui/Tabs'
import { DailyWageTab } from './components/DailyWageTab'
import { MonthlySalaryTab } from './components/MonthlySalaryTab'
import { WorkerFormModal } from './components/WorkerFormModal'
import { useWorkerMutations } from './hooks/useWorkerKhata'

const TABS = [
  { value: 'daily',   label: 'Daily Wage' },
  { value: 'monthly', label: 'Monthly Salary' },
]

export function WorkerKhataPage() {
  const [activeTab, setActiveTab]   = useState('daily')
  const [modalOpen, setModalOpen]   = useState(false)
  const { create }                  = useWorkerMutations()

  const handleAddWorker = (data) => {
    create.mutate(data, { onSuccess: () => setModalOpen(false) })
  }

  return (
    <AnimatedPage>
      <PageHeader
        title="Worker Khata"
        subtitle="Manage daily wages and monthly salaries"
        actions={
          <Button onClick={() => setModalOpen(true)}>+ Add Worker</Button>
        }
      />

      <Card className="mb-4 !p-0">
        <Tabs tabs={TABS} activeTab={activeTab} onChange={setActiveTab} />
      </Card>

      <div className="mt-4">
        {activeTab === 'daily'   && <DailyWageTab />}
        {activeTab === 'monthly' && <MonthlySalaryTab />}
      </div>

      <WorkerFormModal
        open={modalOpen}
        onClose={() => setModalOpen(false)}
        onSubmit={handleAddWorker}
        isPending={create.isPending}
      />
    </AnimatedPage>
  )
}
