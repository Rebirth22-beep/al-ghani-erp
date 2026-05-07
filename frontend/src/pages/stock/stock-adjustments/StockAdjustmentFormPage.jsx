import { useNavigate } from 'react-router-dom'
import { useForm, Controller } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { format } from 'date-fns'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Input } from '@/components/ui/Input'
import { Select } from '@/components/ui/Select'
import { PageHeader } from '@/components/layout/PageHeader'
import { DatePicker } from '@/components/forms/DatePicker'
import { SearchProductInput } from '@/components/forms/SearchProductInput'
import { useStockAdjustmentMutations } from './hooks/useStockAdjustment'

const TYPE_OPTIONS = [
  { value: 'increase', label: 'Increase' },
  { value: 'decrease', label: 'Decrease' },
]

const schema = z.object({
  product_id: z.number({ required_error: 'Product is required' }),
  date:       z.string().min(1, 'Date is required'),
  type:       z.enum(['increase', 'decrease']),
  qty:        z.coerce.number().positive('Quantity must be positive'),
  reason:     z.string().min(1, 'Reason is required'),
})

export function StockAdjustmentFormPage() {
  const navigate  = useNavigate()
  const { create } = useStockAdjustmentMutations()

  const { register, handleSubmit, control, setValue, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
    defaultValues: {
      date: format(new Date(), 'yyyy-MM-dd'),
      type: 'increase',
    },
  })

  const onSubmit = (data) => {
    create.mutate(data, { onSuccess: () => navigate('/stock/stock-adjustments') })
  }

  return (
    <AnimatedPage>
      <PageHeader
        title="New Stock Adjustment"
        actions={
          <Button variant="secondary" onClick={() => navigate('/stock/stock-adjustments')}>
            Cancel
          </Button>
        }
      />

      <Card>
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="flex flex-col gap-1">
              <label className="form-label">Product <span className="text-red-500">*</span></label>
              <Controller
                name="product"
                control={control}
                render={({ field }) => (
                  <SearchProductInput
                    value={field.value}
                    onChange={(p) => { field.onChange(p); setValue('product_id', p.id) }}
                  />
                )}
              />
              {errors.product_id && <p className="text-xs text-red-600">{errors.product_id.message}</p>}
            </div>

            <DatePicker
              label="Date"
              required
              error={errors.date?.message}
              {...register('date')}
            />

            <Select
              label="Adjustment Type"
              required
              options={TYPE_OPTIONS}
              error={errors.type?.message}
              {...register('type')}
            />

            <Input
              label="Quantity"
              type="number"
              step="0.001"
              required
              placeholder="e.g. 10"
              error={errors.qty?.message}
              {...register('qty')}
            />

            <div className="md:col-span-2">
              <label className="form-label">Reason <span className="text-red-500">*</span></label>
              <textarea
                className="form-input w-full mt-1"
                rows={3}
                placeholder="Reason for adjustment (required)"
                {...register('reason')}
              />
              {errors.reason && <p className="text-xs text-red-600 mt-1">{errors.reason.message}</p>}
            </div>
          </div>

          <div className="flex gap-3 justify-end">
            <Button type="submit" loading={create.isPending}>
              Save Adjustment
            </Button>
          </div>
        </form>
      </Card>
    </AnimatedPage>
  )
}
