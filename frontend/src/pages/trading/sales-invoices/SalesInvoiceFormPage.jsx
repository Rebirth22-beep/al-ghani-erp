import { useEffect } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { PageHeader } from '@/components/layout/PageHeader'
import { InvoiceHeader } from './components/InvoiceHeader'
import { ProductLineTable } from './components/ProductLineTable'
import { InvoiceFooter } from './components/InvoiceFooter'
import { useSaleInvoice, useSaleInvoiceMutations } from './hooks/useSaleInvoice'
import { ROUTES } from '@/constants/routes'
import { SEASONS } from '@/constants/seasons'
import { PAYMENT_TYPES } from '@/constants/paymentTypes'
import { format } from 'date-fns'

const schema = z.object({
  party_id:        z.number({ required_error: 'Party is required' }),
  date:            z.string().min(1, 'Date is required'),
  payment_type:    z.enum(Object.values(PAYMENT_TYPES)),
  season:          z.enum(Object.values(SEASONS)),
  bill_book_number:z.string().optional(),
  lines:           z.array(z.object({
    product_id:   z.number(),
    quantity:     z.coerce.number().positive(),
    unit:         z.string(),
    rate_paisas:  z.number(),
    total_paisas: z.number(),
    batch_id:     z.string().optional(),
  })).min(1, 'At least one product is required'),
})

export function SalesInvoiceFormPage() {
  const { id }    = useParams()
  const navigate  = useNavigate()
  const isEdit    = !!id

  const { data: existing } = useSaleInvoice(id)
  const { create, update } = useSaleInvoiceMutations()

  const form = useForm({
    resolver: zodResolver(schema),
    defaultValues: {
      date:         format(new Date(), 'yyyy-MM-dd'),
      payment_type: PAYMENT_TYPES.CASH,
      season:       SEASONS.NONE,
      lines:        [],
    },
  })

  const { handleSubmit, control, register, setValue, watch, formState: { errors } } = form

  useEffect(() => {
    if (existing) {
      Object.entries(existing).forEach(([key, val]) => setValue(key, val))
    }
  }, [existing, setValue])

  const lines       = watch('lines') || []
  const totalPaisas = lines.reduce((sum, l) => sum + (l.total_paisas || 0), 0)

  const onSubmit = (data) => {
    const payload = { ...data, total_paisas: totalPaisas }
    if (isEdit) {
      update.mutate({ id, data: payload }, { onSuccess: () => navigate(ROUTES.SALES_INVOICES) })
    } else {
      create.mutate(payload, { onSuccess: () => navigate(ROUTES.SALES_INVOICES) })
    }
  }

  const isPending = create.isPending || update.isPending

  return (
    <AnimatedPage>
      <PageHeader
        title={isEdit ? 'Edit Invoice' : 'New Sale Invoice'}
        actions={
          <Button variant="secondary" onClick={() => navigate(ROUTES.SALES_INVOICES)}>
            Cancel
          </Button>
        }
      />

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <Card title="Invoice Details">
          <InvoiceHeader
            control={control}
            register={register}
            errors={errors}
            setValue={setValue}
          />
        </Card>

        <Card title="Products">
          <ProductLineTable
            control={control}
            register={register}
            errors={errors}
            setValue={setValue}
            watch={watch}
          />
          {errors.lines && <p className="text-xs text-red-600 mt-2">{errors.lines.message}</p>}
        </Card>

        <Card>
          <InvoiceFooter totalPaisas={totalPaisas} />
        </Card>

        <div className="flex gap-3 justify-end">
          <Button type="submit" loading={isPending}>
            {isEdit ? 'Update Invoice' : 'Save Invoice'}
          </Button>
        </div>
      </form>
    </AnimatedPage>
  )
}
