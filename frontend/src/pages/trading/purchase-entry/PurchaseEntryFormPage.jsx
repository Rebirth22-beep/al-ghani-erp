import { useEffect } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { format } from 'date-fns'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { PageHeader } from '@/components/layout/PageHeader'
import { PurchaseHeader } from './components/PurchaseHeader'
import { PurchaseLineTable } from './components/PurchaseLineTable'
import { usePurchaseEntry, usePurchaseEntryMutations } from './hooks/usePurchaseEntry'
import { formatCurrency } from '@/utils/formatCurrency'

const schema = z.object({
  party_id:         z.number({ required_error: 'Supplier is required' }),
  date:             z.string().min(1, 'Date is required'),
  reference:        z.string().optional(),
  bill_book_number: z.string().optional(),
  lines:            z.array(z.object({
    product_id:       z.number(),
    quantity:         z.coerce.number().positive(),
    unit:             z.string(),
    cost_rate_paisas: z.number(),
    total_paisas:     z.number(),
  })).min(1, 'At least one product is required'),
})

export function PurchaseEntryFormPage() {
  const { id }    = useParams()
  const navigate  = useNavigate()
  const isEdit    = !!id

  const { data: existing }  = usePurchaseEntry(id)
  const { create, update }  = usePurchaseEntryMutations()

  const form = useForm({
    resolver: zodResolver(schema),
    defaultValues: {
      date:  format(new Date(), 'yyyy-MM-dd'),
      lines: [],
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
      update.mutate({ id, data: payload }, { onSuccess: () => navigate('/trading/purchase-entry') })
    } else {
      create.mutate(payload, { onSuccess: () => navigate('/trading/purchase-entry') })
    }
  }

  const isPending = create.isPending || update.isPending

  return (
    <AnimatedPage>
      <PageHeader
        title={isEdit ? 'Edit Purchase Entry' : 'New Purchase Entry'}
        actions={
          <Button variant="secondary" onClick={() => navigate('/trading/purchase-entry')}>
            Cancel
          </Button>
        }
      />

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <Card title="Purchase Details">
          <PurchaseHeader
            control={control}
            register={register}
            errors={errors}
            setValue={setValue}
          />
        </Card>

        <Card title="Products">
          <PurchaseLineTable
            control={control}
            register={register}
            errors={errors}
            setValue={setValue}
            watch={watch}
          />
          {errors.lines && <p className="text-xs text-red-600 mt-2">{errors.lines.message}</p>}
        </Card>

        <Card>
          <div className="flex justify-end">
            <div className="text-right">
              <p className="text-sm text-gray-500">Total Amount</p>
              <p className="text-xl font-bold text-gray-900">{formatCurrency(totalPaisas)}</p>
            </div>
          </div>
        </Card>

        <div className="flex gap-3 justify-end">
          <Button type="submit" loading={isPending}>
            {isEdit ? 'Update Entry' : 'Save Entry'}
          </Button>
        </div>
      </form>
    </AnimatedPage>
  )
}
