import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { usePartyLedger } from './hooks/usePartyLedger'
import { PartyLedgerTable } from './components/PartyLedgerTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Select } from '@/components/ui/Select'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'

const PARTY_TYPE_OPTIONS = [
  { value: '', label: 'All Parties' },
  { value: 'customer', label: 'Customers' },
  { value: 'supplier', label: 'Suppliers' },
]

export function PartyLedgerPage() {
  const [page, setPage]         = useState(1)
  const [search, setSearch]     = useState('')
  const [partyType, setPartyType] = useState('')
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo]     = useState('')

  const { data, isLoading } = usePartyLedger({
    page,
    search,
    party_type: partyType,
    date_from:  dateFrom,
    date_to:    dateTo,
    per_page:   30,
  })

  return (
    <AnimatedPage>
      <PageHeader title="Party Ledger" subtitle="Combined ledger for all parties" />

      <Card className="mb-4">
        <div className="flex flex-wrap gap-4 items-end">
          <div className="w-48">
            <Input
              label="Search Party"
              placeholder="Name..."
              value={search}
              onChange={(e) => { setSearch(e.target.value); setPage(1) }}
            />
          </div>
          <div className="w-40">
            <Select
              label="Party Type"
              options={PARTY_TYPE_OPTIONS}
              value={partyType}
              onChange={(e) => { setPartyType(e.target.value); setPage(1) }}
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
          <Button variant="secondary" onClick={() => { setSearch(''); setPartyType(''); setDateFrom(''); setDateTo(''); setPage(1) }}>
            Clear
          </Button>
        </div>
      </Card>

      <PartyLedgerTable data={data?.data} loading={isLoading} />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
