import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useGeneralLedger } from './hooks/useGeneralLedger'
import { LedgerTable } from './components/LedgerTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'

export function GeneralLedgerPage() {
  const [page, setPage]         = useState(1)
  const [account, setAccount]   = useState('')
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo]     = useState('')

  const { data, isLoading } = useGeneralLedger({
    page,
    account,
    date_from: dateFrom,
    date_to:   dateTo,
    per_page:  30,
  })

  return (
    <AnimatedPage>
      <PageHeader title="General Ledger" subtitle="Full accounting ledger" />

      <Card className="mb-4">
        <div className="flex flex-wrap gap-4 items-end">
          <div className="w-48">
            <Input
              label="Account Filter"
              placeholder="Account name..."
              value={account}
              onChange={(e) => { setAccount(e.target.value); setPage(1) }}
            />
          </div>
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
          <Button variant="secondary" onClick={() => { setAccount(''); setDateFrom(''); setDateTo(''); setPage(1) }}>
            Clear
          </Button>
        </div>
      </Card>

      <LedgerTable data={data?.data} loading={isLoading} />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
