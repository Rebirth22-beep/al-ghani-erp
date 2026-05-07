import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useProductList, useProductMutations } from './hooks/useProduct'
import { ProductTable } from './components/ProductTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'

export function ProductRegisterPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = useProductList({ page, search, per_page: 20 })
  const { remove }          = useProductMutations()

  return (
    <AnimatedPage>
      <PageHeader
        title="Product Register"
        subtitle={`${data?.meta?.total ?? 0} products`}
        actions={
          <Link to="/stock/product-register/new">
            <Button>+ New Product</Button>
          </Link>
        }
      />

      <div className="mb-4 max-w-xs">
        <Input
          placeholder="Search by name, category..."
          value={search}
          onChange={(e) => { setSearch(e.target.value); setPage(1) }}
        />
      </div>

      <ProductTable
        data={data?.data}
        loading={isLoading}
        onDelete={(id) => remove.mutate(id)}
      />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
