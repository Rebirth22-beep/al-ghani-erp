import { useState } from 'react'
import { Link } from 'react-router-dom'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useSaleInvoiceList, useSaleInvoiceMutations } from './hooks/useSaleInvoice'
import { PageHeader } from '@/components/layout/PageHeader'
import { Table } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { Button } from '@/components/ui/Button'
import { Pagination } from '@/components/ui/Pagination'
import { Input } from '@/components/ui/Input'
import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'
import { ROUTES } from '@/constants/routes'

const STATUS_VARIANT = { draft: 'warning', posted: 'success', cancelled: 'danger' }

export function SalesInvoicePage() {
  const [page, setPage]     = useState(1)
  const [search, setSearch] = useState('')
  const { data, isLoading } = useSaleInvoiceList({ page, search, per_page: 20 })
  const { remove, post }    = useSaleInvoiceMutations()

  const columns = [
    { key: 'bill_number',  title: 'Bill #',   width: 100 },
    { key: 'date',         title: 'Date',      render: (v) => formatDate(v) },
    { key: 'party_name',   title: 'Party' },
    { key: 'season',       title: 'Season',    render: (v) => <Badge>{v}</Badge> },
    { key: 'payment_type', title: 'Payment',   render: (v) => <Badge variant={v === 'cash' ? 'success' : 'info'}>{v}</Badge> },
    { key: 'total_paisas', title: 'Total',     render: (v) => <span className="font-medium">{formatCurrency(v)}</span> },
    { key: 'status',       title: 'Status',    render: (v) => <Badge variant={STATUS_VARIANT[v]}>{v}</Badge> },
    {
      key: 'id',
      title: 'Actions',
      render: (id, row) => (
        <div className="flex items-center gap-2">
          <Link to={`/trading/sales-invoices/${id}/edit`} className="text-xs text-brand hover:underline">Edit</Link>
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
        title="Sales Invoices"
        subtitle={`${data?.meta?.total ?? 0} invoices`}
        actions={
          <Link to={ROUTES.SALES_INVOICE_NEW}>
            <Button>+ New Invoice</Button>
          </Link>
        }
      />

      <div className="mb-4 max-w-xs">
        <Input placeholder="Search by bill #, party..." value={search} onChange={(e) => { setSearch(e.target.value); setPage(1) }} />
      </div>

      <Table columns={columns} data={data?.data} loading={isLoading} />
      <Pagination meta={data?.meta} onPageChange={setPage} />
    </AnimatedPage>
  )
}
