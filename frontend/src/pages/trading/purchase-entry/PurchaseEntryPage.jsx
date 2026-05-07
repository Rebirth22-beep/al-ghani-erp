import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { usePurchaseEntryList, usePurchaseEntryMutations } from './hooks/usePurchaseEntry'
import { PageHeader } from '@/components/layout/PageHeader'
import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

const STATUS_VARIANT = { draft: 'warning', posted: 'success', cancelled: 'danger' }

export function PurchaseEntryPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = usePurchaseEntryList({ page, search, per_page: 20 })
  const { remove, post }    = usePurchaseEntryMutations()

  const columns = [
    { key: 'reference',    title: 'Ref #',     width: 120 },
    { key: 'date',         title: 'Date',       render: (v) => formatDate(v) },
    { key: 'party_name',   title: 'Supplier' },
    { key: 'total_paisas', title: 'Total',      render: (v) => <span className="font-medium">{formatCurrency(v)}</span> },
    { key: 'status',       title: 'Status',     render: (v) => <Badge variant={STATUS_VARIANT[v]}>{v}</Badge> },
    {
      key: 'id',
      title: 'Actions',
      render: (id, row) => (
        <div className="flex items-center gap-2">
          <Link to={`/trading/purchase-entry/${id}/edit`} className="text-xs text-brand hover:underline">Edit</Link>
          {row.status === 'draft' && (
            <button onClick={() => post.mutate(id)} className="text-xs text-green-600 hover:underline">Post</button>
          )}
          {row.status === 'draft' && (
            <button onClick={() => remove.mutate(id)} className="text-xs text-red-500 hover:underline">Delete</button>
          )}
        </div>
      ),
    },
  ]

  return (
    <AnimatedPage>
      <PageHeader
        title="Purchase Entries"
        subtitle={`${data?.meta?.total ?? 0} entries`}
        actions={
          <Link to="/trading/purchase-entry/new">
            <Button>+ New Purchase Entry</Button>
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
