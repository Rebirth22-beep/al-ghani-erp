import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useSalesReturnList, useSalesReturnMutations } from './hooks/useSalesReturn'
import { PageHeader } from '@/components/layout/PageHeader'
import { Table } from '@/components/ui/Table'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function SalesReturnPage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = useSalesReturnList({ page, search, per_page: 20 })
  const { remove }          = useSalesReturnMutations()

  const columns = [
    { key: 'original_invoice', title: 'Invoice #',  width: 120 },
    { key: 'date',             title: 'Date',        render: (v) => formatDate(v) },
    { key: 'party_name',       title: 'Party' },
    { key: 'total_paisas',     title: 'Total',       render: (v) => <span className="font-medium">{formatCurrency(v)}</span> },
    {
      key: 'id',
      title: 'Actions',
      render: (id) => (
        <div className="flex items-center gap-2">
          <Link to={`/trading/sales-returns/${id}/edit`} className="text-xs text-brand hover:underline">Edit</Link>
          <button onClick={() => remove.mutate(id)} className="text-xs text-red-500 hover:underline">Delete</button>
        </div>
      ),
    },
  ]

  return (
    <AnimatedPage>
      <PageHeader
        title="Sales Returns"
        subtitle={`${data?.meta?.total ?? 0} returns`}
        actions={
          <Link to="/trading/sales-returns/new">
            <Button>+ New Sales Return</Button>
          </Link>
        }
      />

      <div className="mb-4 max-w-xs">
        <Input
          placeholder="Search by invoice #, party..."
          value={search}
          onChange={(e) => { setSearch(e.target.value); setPage(1) }}
        />
      </div>

      <Table columns={columns} data={data?.data} loading={isLoading} />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
