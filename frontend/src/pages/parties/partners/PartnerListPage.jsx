import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { usePartnerList, usePartnerMutations } from './hooks/usePartner'
import { PartnerTable } from './components/PartnerTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'

export function PartnerListPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = usePartnerList({ page, search, per_page: 20 })
  const { remove }          = usePartnerMutations()

  return (
    <AnimatedPage>
      <PageHeader
        title="Partners"
        subtitle={`${data?.meta?.total ?? 0} partners`}
        actions={
          <Link to="/parties/partners/new">
            <Button>+ New Partner</Button>
          </Link>
        }
      />

      <div className="mb-4 max-w-xs">
        <Input
          placeholder="Search by name..."
          value={search}
          onChange={(e) => { setSearch(e.target.value); setPage(1) }}
        />
      </div>

      <PartnerTable
        data={data?.data}
        loading={isLoading}
        onDelete={(id) => remove.mutate(id)}
      />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
