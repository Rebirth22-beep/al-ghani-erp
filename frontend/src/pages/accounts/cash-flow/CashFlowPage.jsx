import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useCashFlow } from './hooks/useCashFlow'
import { CashFlowTable } from './components/CashFlowTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'

export function CashFlowPage() {
  const [page, setPage]         = useState(1)
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo]     = useState('')
  const [applied, setApplied]   = useState({})

  const { data, isLoading } = useCashFlow({ ...applied, page, per_page: 30 })

  const handleApply = () => { setApplied({ date_from: dateFrom, date_to: dateTo }); setPage(1) }

  return (
    <AnimatedPage>
      <PageHeader title="Cash Flow" subtitle="Cash inflows and outflows" />

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
          <Button variant="secondary" onClick={() => { setDateFrom(''); setDateTo(''); setApplied({}); setPage(1) }}>Clear</Button>
        </div>
      </Card>

      <CashFlowTable data={data?.data} loading={isLoading} />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
