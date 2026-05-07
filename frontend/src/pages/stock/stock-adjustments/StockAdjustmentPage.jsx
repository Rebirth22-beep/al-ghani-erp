import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useStockAdjustmentList, useStockAdjustmentMutations } from './hooks/useStockAdjustment'
import { AdjustmentTable } from './components/AdjustmentTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'

export function StockAdjustmentPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = useStockAdjustmentList({ page, search, per_page: 20 })
  const { remove }          = useStockAdjustmentMutations()

  return (
    <AnimatedPage>
      <PageHeader
        title="Stock Adjustments"
        subtitle={`${data?.meta?.total ?? 0} adjustments`}
        actions={
          <Link to="/stock/stock-adjustments/new">
            <Button>+ New Adjustment</Button>
          </Link>
        }
      />

      <div className="mb-4 max-w-xs">
        <Input
          placeholder="Search by product, reason..."
          value={search}
          onChange={(e) => { setSearch(e.target.value); setPage(1) }}
        />
      </div>

      <AdjustmentTable
        data={data?.data}
        loading={isLoading}
        onDelete={(id) => remove.mutate(id)}
      />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
