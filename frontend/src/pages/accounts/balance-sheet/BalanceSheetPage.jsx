import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useBalanceSheet } from './hooks/useBalanceSheet'
import { BalanceSheetTable } from './components/BalanceSheetTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'

export function BalanceSheetPage() {
  const [asOfDate, setAsOfDate] = useState('')
  const [applied, setApplied]   = useState({})

  const { data, isLoading } = useBalanceSheet(applied)

  const handleApply = () => setApplied({ as_of: asOfDate })

  return (
    <AnimatedPage>
      <PageHeader title="Balance Sheet" subtitle="Assets, liabilities, and equity" />

      <Card className="mb-4">
        <div className="flex flex-wrap gap-4 items-end">
          <Input
            label="As of Date"
            type="date"
            value={asOfDate}
            onChange={(e) => setAsOfDate(e.target.value)}
          />
          <Button onClick={handleApply}>Apply</Button>
          <Button variant="secondary" onClick={() => { setAsOfDate(''); setApplied({}) }}>Clear</Button>
        </div>
      </Card>

      <Card>
        <BalanceSheetTable data={data} loading={isLoading} />
      </Card>
    </AnimatedPage>
  )
}
