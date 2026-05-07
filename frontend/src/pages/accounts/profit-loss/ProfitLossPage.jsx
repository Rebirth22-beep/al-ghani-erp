import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useProfitLoss } from './hooks/useProfitLoss'
import { ProfitLossTable } from './components/ProfitLossTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'

export function ProfitLossPage() {
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo]     = useState('')
  const [applied, setApplied]   = useState({})

  const { data, isLoading } = useProfitLoss(applied)

  const handleApply = () => setApplied({ date_from: dateFrom, date_to: dateTo })

  return (
    <AnimatedPage>
      <PageHeader title="Profit & Loss" subtitle="Revenue and expense summary" />

      <Card className="mb-4">
        <div className="flex flex-wrap gap-4 items-end">
          <Input
            label="From Date"
            type="date"
            value={dateFrom}
            onChange={(e) => setDateFrom(e.target.value)}
          />
          <Input
            label="To Date"
            type="date"
            value={dateTo}
            onChange={(e) => setDateTo(e.target.value)}
          />
          <Button onClick={handleApply}>Apply</Button>
          <Button variant="secondary" onClick={() => { setDateFrom(''); setDateTo(''); setApplied({}) }}>Clear</Button>
        </div>
      </Card>

      <Card>
        <ProfitLossTable data={data} loading={isLoading} />
      </Card>
    </AnimatedPage>
  )
}
