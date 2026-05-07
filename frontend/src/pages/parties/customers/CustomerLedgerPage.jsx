import { useState } from 'react'
import { useParams, Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useCustomer, useCustomerLedger } from './hooks/useCustomer'
import { CustomerLedgerTable } from './components/CustomerLedgerTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { formatCurrency } from '@/utils/formatCurrency'

export function CustomerLedgerPage() {
  const { id }              = useParams()
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo]     = useState('')

  const { data: customer }  = useCustomer(id)
  const { data, isLoading } = useCustomerLedger(id, { date_from: dateFrom, date_to: dateTo })

  return (
    <AnimatedPage>
      <PageHeader
        title={`Ledger: ${customer?.name || '...'}`}
        subtitle={`Balance: ${formatCurrency(customer?.balance_paisas)}`}
        actions={
          <Link to="/parties/customers">
            <Button variant="secondary">Back to Customers</Button>
          </Link>
        }
      />

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
          <Button variant="secondary" onClick={() => { setDateFrom(''); setDateTo('') }}>
            Clear
          </Button>
        </div>
      </Card>

      <CustomerLedgerTable data={data?.data} loading={isLoading} />
    </AnimatedPage>
  )
}
