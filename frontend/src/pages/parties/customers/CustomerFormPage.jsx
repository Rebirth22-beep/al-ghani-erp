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
import { useCustomer, useCustomerMutations } from './hooks/useCustomer'
import { toPaisas, toRupees } from '@/utils/formatCurrency'

const schema = z.object({
  name:            z.string().min(1, 'Name is required'),
  phone:           z.string().optional(),
  address:         z.string().optional(),
  city:            z.string().optional(),
  opening_balance: z.coerce.number().default(0),
})

export function CustomerFormPage() {
  const { id }    = useParams()
  const navigate  = useNavigate()
  const isEdit    = !!id

  const { data: existing } = useCustomer(id)
  const { create, update } = useCustomerMutations()

  const { register, handleSubmit, setValue, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
    defaultValues: { opening_balance: 0 },
  })

  useEffect(() => {
    if (existing) {
      setValue('name',            existing.name)
      setValue('phone',           existing.phone)
      setValue('address',         existing.address)
      setValue('city',            existing.city)
      setValue('opening_balance', toRupees(existing.opening_balance_paisas || 0))
    }
  }, [existing, setValue])

  const onSubmit = (data) => {
    const payload = {
      ...data,
      opening_balance_paisas: toPaisas(data.opening_balance),
    }
    delete payload.opening_balance

    if (isEdit) {
      update.mutate({ id, data: payload }, { onSuccess: () => navigate('/parties/customers') })
    } else {
      create.mutate(payload, { onSuccess: () => navigate('/parties/customers') })
    }
  }

  const isPending = create.isPending || update.isPending

  return (
    <AnimatedPage>
      <PageHeader
        title={isEdit ? 'Edit Customer' : 'New Customer'}
        actions={
          <Button variant="secondary" onClick={() => navigate('/parties/customers')}>
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
              placeholder="Customer name"
              error={errors.name?.message}
              {...register('name')}
            />
            <Input
              label="Phone"
              placeholder="e.g. 0300-1234567"
              error={errors.phone?.message}
              {...register('phone')}
            />
            <Input
              label="City"
              placeholder="e.g. Lahore"
              error={errors.city?.message}
              {...register('city')}
            />
            <Input
              label="Opening Balance (Rs)"
              type="number"
              step="0.01"
              placeholder="0.00"
              error={errors.opening_balance?.message}
              {...register('opening_balance')}
            />
            <div className="md:col-span-2">
              <Input
                label="Address"
                placeholder="Full address"
                error={errors.address?.message}
                {...register('address')}
              />
            </div>
          </div>

          <div className="flex gap-3 justify-end">
            <Button type="submit" loading={isPending}>
              {isEdit ? 'Update Customer' : 'Save Customer'}
            </Button>
          </div>
        </form>
      </Card>
    </AnimatedPage>
  )
}
