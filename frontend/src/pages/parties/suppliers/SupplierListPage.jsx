import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useSupplierList, useSupplierMutations } from './hooks/useSupplier'
import { SupplierTable } from './components/SupplierTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'

export function SupplierListPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = useSupplierList({ page, search, per_page: 20 })
  const { remove }          = useSupplierMutations()

  return (
    <AnimatedPage>
      <PageHeader
        title="Suppliers"
        subtitle={`${data?.meta?.total ?? 0} suppliers`}
        actions={
          <Link to="/parties/suppliers/new">
            <Button>+ New Supplier</Button>
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

      <SupplierTable
        data={data?.data}
        loading={isLoading}
        onDelete={(id) => remove.mutate(id)}
      />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
