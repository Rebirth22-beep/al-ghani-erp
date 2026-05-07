import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useCustomerList, useCustomerMutations } from './hooks/useCustomer'
import { CustomerTable } from './components/CustomerTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'

export function CustomerListPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = useCustomerList({ page, search, per_page: 20 })
  const { remove }          = useCustomerMutations()

  return (
    <AnimatedPage>
      <PageHeader
        title="Customers"
        subtitle={`${data?.meta?.total ?? 0} customers`}
        actions={
          <Link to="/parties/customers/new">
            <Button>+ New Customer</Button>
          </Link>
        }
      />

      <div className="mb-4 max-w-xs">
        <Input
          placeholder="Search by name, phone..."
          value={search}
          onChange={(e) => { setSearch(e.target.value); setPage(1) }}
        />
      </div>

      <CustomerTable
        data={data?.data}
        loading={isLoading}
        onDelete={(id) => remove.mutate(id)}
      />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
