import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useTrialBalance } from './hooks/useTrialBalance'
import { TrialBalanceTable } from './components/TrialBalanceTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'

export function TrialBalancePage() {
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo]     = useState('')
  const [applied, setApplied]   = useState({})

  const { data, isLoading } = useTrialBalance(applied)

  const handleApply = () => setApplied({ date_from: dateFrom, date_to: dateTo })

  return (
    <AnimatedPage>
      <PageHeader title="Trial Balance" subtitle="Debit and credit totals for all accounts" />

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

      <TrialBalanceTable data={data} loading={isLoading} />
    </AnimatedPage>
  )
}
