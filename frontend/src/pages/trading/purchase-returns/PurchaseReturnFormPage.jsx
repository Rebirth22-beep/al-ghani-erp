import { useEffect } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { useForm, Controller } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { format } from 'date-fns'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Input } from '@/components/ui/Input'
import { PageHeader } from '@/components/layout/PageHeader'
import { DatePicker } from '@/components/forms/DatePicker'
import { SearchPartyInput } from '@/components/forms/SearchPartyInput'
import { PurchaseReturnLineTable } from './components/PurchaseReturnLineTable'
import { usePurchaseReturn, usePurchaseReturnMutations } from './hooks/usePurchaseReturn'
import { formatCurrency } from '@/utils/formatCurrency'

const schema = z.object({
  original_reference: z.string().min(1, 'Original purchase reference is required'),
  party_id:           z.number({ required_error: 'Supplier is required' }),
  date:               z.string().min(1, 'Date is required'),
  lines:              z.array(z.object({
    product_id:       z.number(),
    return_qty:       z.coerce.number().positive(),
    unit:             z.string(),
    cost_rate_paisas: z.number(),
    total_paisas:     z.number(),
  })).min(1, 'At least one product is required'),
})

export function PurchaseReturnFormPage() {
  const { id }    = useParams()
  const navigate  = useNavigate()
  const isEdit    = !!id

  const { data: existing } = usePurchaseReturn(id)
  const { create }         = usePurchaseReturnMutations()

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
    create.mutate(payload, { onSuccess: () => navigate('/trading/purchase-returns') })
  }

  return (
    <AnimatedPage>
      <PageHeader
        title={isEdit ? 'Edit Purchase Return' : 'New Purchase Return'}
        actions={
          <Button variant="secondary" onClick={() => navigate('/trading/purchase-returns')}>
            Cancel
          </Button>
        }
      />

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <Card title="Return Details">
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <Input
              label="Original PO Reference #"
              placeholder="e.g. PO-001"
              required
              error={errors.original_reference?.message}
              {...register('original_reference')}
            />

            <div className="flex flex-col gap-1">
              <label className="form-label">Supplier <span className="text-red-500">*</span></label>
              <Controller
                name="party"
                control={control}
                render={({ field }) => (
                  <SearchPartyInput
                    type="supplier"
                    value={field.value}
                    onChange={(p) => { field.onChange(p); setValue('party_id', p.id) }}
                  />
                )}
              />
              {errors.party_id && <p className="text-xs text-red-600">{errors.party_id.message}</p>}
            </div>

            <DatePicker
              label="Date"
              required
              error={errors.date?.message}
              {...register('date')}
            />
          </div>
        </Card>

        <Card title="Return Items">
          <PurchaseReturnLineTable
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
              <p className="text-sm text-gray-500">Total Return Amount</p>
              <p className="text-xl font-bold text-gray-900">{formatCurrency(totalPaisas)}</p>
            </div>
          </div>
        </Card>

        <div className="flex gap-3 justify-end">
          <Button type="submit" loading={create.isPending}>
            Save Return
          </Button>
        </div>
      </form>
    </AnimatedPage>
  )
}
