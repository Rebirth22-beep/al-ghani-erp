import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useBatchList } from './hooks/useBatch'
import { BatchTable } from './components/BatchTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Pagination } from '@/components/ui/Pagination'

export function BatchTrackingPage() {
  const [page, setPage]         = useState(1)
  const [search, setSearch]     = useState('')
  const [expiryFrom, setExpiryFrom] = useState('')
  const [expiryTo, setExpiryTo]     = useState('')

  const { data, isLoading } = useBatchList({
    page,
    search,
    expiry_from: expiryFrom,
    expiry_to:   expiryTo,
    per_page:    20,
  })

  return (
    <AnimatedPage>
      <PageHeader title="Batch Tracking" subtitle="View all product batches and expiry dates" />

      <Card className="mb-4">
        <div className="flex flex-wrap gap-4 items-end">
          <div className="w-48">
            <Input
              label="Search"
              placeholder="Batch #, product..."
              value={search}
              onChange={(e) => { setSearch(e.target.value); setPage(1) }}
            />
          </div>
          <Input
            label="Expiry From"
            type="date"
            value={expiryFrom}
            onChange={(e) => setExpiryFrom(e.target.value)}
          />
          <Input
            label="Expiry To"
            type="date"
            value={expiryTo}
            onChange={(e) => setExpiryTo(e.target.value)}
          />
        </div>
      </Card>

      <BatchTable data={data?.data} loading={isLoading} />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
