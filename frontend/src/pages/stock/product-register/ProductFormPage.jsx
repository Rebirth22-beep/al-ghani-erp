import { useEffect } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Input } from '@/components/ui/Input'
import { PageHeader } from '@/components/layout/PageHeader'
import { useProduct, useProductMutations } from './hooks/useProduct'
import { toPaisas, toRupees } from '@/utils/formatCurrency'

const schema = z.object({
  name:             z.string().min(1, 'Name is required'),
  category:         z.string().optional(),
  unit:             z.string().min(1, 'Unit is required'),
  pack_size:        z.string().optional(),
  purchase_rate:    z.coerce.number().min(0).default(0),
  sale_rate:        z.coerce.number().min(0).default(0),
  min_stock:        z.coerce.number().min(0).default(0),
  description:      z.string().optional(),
})

export function ProductFormPage() {
  const { id }    = useParams()
  const navigate  = useNavigate()
  const isEdit    = !!id

  const { data: existing } = useProduct(id)
  const { create, update } = useProductMutations()

  const { register, handleSubmit, setValue, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
    defaultValues: { purchase_rate: 0, sale_rate: 0, min_stock: 0 },
  })

  useEffect(() => {
    if (existing) {
      setValue('name',          existing.name)
      setValue('category',      existing.category)
      setValue('unit',          existing.unit)
      setValue('pack_size',     existing.pack_size)
      setValue('purchase_rate', toRupees(existing.purchase_rate_paisas || 0))
      setValue('sale_rate',     toRupees(existing.sale_rate_paisas || 0))
      setValue('min_stock',     existing.min_stock || 0)
      setValue('description',   existing.description)
    }
  }, [existing, setValue])

  const onSubmit = (data) => {
    const payload = {
      ...data,
      purchase_rate_paisas: toPaisas(data.purchase_rate),
      sale_rate_paisas:     toPaisas(data.sale_rate),
    }
    delete payload.purchase_rate
    delete payload.sale_rate

    if (isEdit) {
      update.mutate({ id, data: payload }, { onSuccess: () => navigate('/stock/product-register') })
    } else {
      create.mutate(payload, { onSuccess: () => navigate('/stock/product-register') })
    }
  }

  const isPending = create.isPending || update.isPending

  return (
    <AnimatedPage>
      <PageHeader
        title={isEdit ? 'Edit Product' : 'New Product'}
        actions={
          <Button variant="secondary" onClick={() => navigate('/stock/product-register')}>
            Cancel
          </Button>
        }
      />

      <Card>
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Input
              label="Name"
              required
              placeholder="Product name"
              error={errors.name?.message}
              {...register('name')}
            />
            <Input
              label="Category"
              placeholder="e.g. Fertilizer, Pesticide"
              error={errors.category?.message}
              {...register('category')}
            />
            <Input
              label="Unit"
              required
              placeholder="e.g. kg, liter, bag"
              error={errors.unit?.message}
              {...register('unit')}
            />
            <Input
              label="Pack Size"
              placeholder="e.g. 50kg, 1L"
              error={errors.pack_size?.message}
              {...register('pack_size')}
            />
            <Input
              label="Purchase Rate (Rs)"
              type="number"
              step="0.01"
              placeholder="0.00"
              error={errors.purchase_rate?.message}
              {...register('purchase_rate')}
            />
            <Input
              label="Sale Rate (Rs)"
              type="number"
              step="0.01"
              placeholder="0.00"
              error={errors.sale_rate?.message}
              {...register('sale_rate')}
            />
            <Input
              label="Minimum Stock (for alerts)"
              type="number"
              step="0.001"
              placeholder="0"
              error={errors.min_stock?.message}
              {...register('min_stock')}
            />
            <div className="md:col-span-2">
              <Input
                label="Description"
                placeholder="Optional description"
                error={errors.description?.message}
                {...register('description')}
              />
            </div>
          </div>

          <div className="flex gap-3 justify-end">
            <Button type="submit" loading={isPending}>
              {isEdit ? 'Update Product' : 'Save Product'}
            </Button>
          </div>
        </form>
      </Card>
    </AnimatedPage>
  )
}
