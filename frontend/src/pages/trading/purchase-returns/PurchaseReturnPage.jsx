import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { usePurchaseReturnList, usePurchaseReturnMutations } from './hooks/usePurchaseReturn'
import { PageHeader } from '@/components/layout/PageHeader'
import { Table } from '@/components/ui/Table'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function PurchaseReturnPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = usePurchaseReturnList({ page, search, per_page: 20 })
  const { remove }          = usePurchaseReturnMutations()

  const columns = [
    { key: 'original_reference', title: 'PO Ref #',   width: 120 },
    { key: 'date',               title: 'Date',        render: (v) => formatDate(v) },
    { key: 'party_name',         title: 'Supplier' },
    { key: 'total_paisas',       title: 'Total',       render: (v) => <span className="font-medium">{formatCurrency(v)}</span> },
    {
      key: 'id',
      title: 'Actions',
      render: (id) => (
        <div className="flex items-center gap-2">
          <Link to={`/trading/purchase-returns/${id}/edit`} className="text-xs text-brand hover:underline">Edit</Link>
          <button onClick={() => remove.mutate(id)} className="text-xs text-red-500 hover:underline">Delete</button>
        </div>
      ),
    },
  ]

  return (
    <AnimatedPage>
      <PageHeader
        title="Purchase Returns"
        subtitle={`${data?.meta?.total ?? 0} returns`}
        actions={
          <Link to="/trading/purchase-returns/new">
            <Button>+ New Purchase Return</Button>
          </Link>
        }
      />

      <div className="mb-4 max-w-xs">
        <Input
          placeholder="Search by ref #, supplier..."
          value={search}
          onChange={(e) => { setSearch(e.target.value); setPage(1) }}
        />
      </div>

      <Table columns={columns} data={data?.data} loading={isLoading} />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
