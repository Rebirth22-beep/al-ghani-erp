import { useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { Modal } from '@/components/ui/Modal'
import { Input } from '@/components/ui/Input'
import { Select } from '@/components/ui/Select'
import { Button } from '@/components/ui/Button'

const schema = z.object({
  name:     z.string().min(1, 'Name is required'),
  pay_type: z.enum(['daily', 'monthly']),
  rate:     z.coerce.number().positive('Rate must be positive'),
  phone:    z.string().optional(),
})

const PAY_TYPE_OPTIONS = [
  { value: 'daily',   label: 'Daily Wage' },
  { value: 'monthly', label: 'Monthly Salary' },
]

export function WorkerFormModal({ open, onClose, onSubmit, defaultValues, isPending }) {
  const { register, handleSubmit, reset, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
    defaultValues: { pay_type: 'daily', rate: 0 },
  })

  useEffect(() => {
    if (defaultValues) reset(defaultValues)
    else reset({ pay_type: 'daily', rate: 0 })
  }, [defaultValues, reset])

  const handleClose = () => {
    reset()
    onClose()
  }

  return (
    <Modal open={open} onClose={handleClose} title={defaultValues ? 'Edit Worker' : 'Add Worker'}>
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <Input
          label="Name"
          required
          placeholder="Worker name"
          error={errors.name?.message}
          {...register('name')}
        />
        <Select
          label="Pay Type"
          required
          options={PAY_TYPE_OPTIONS}
          error={errors.pay_type?.message}
          {...register('pay_type')}
        />
        <Input
          label="Rate (Rs)"
          type="number"
          step="0.01"
          required
          placeholder="Rate per day or per month"
          error={errors.rate?.message}
          {...register('rate')}
        />
        <Input
          label="Phone"
          placeholder="e.g. 0300-1234567"
          error={errors.phone?.message}
          {...register('phone')}
        />
        <div className="flex gap-3 justify-end">
          <Button type="button" variant="secondary" onClick={handleClose}>Cancel</Button>
          <Button type="submit" loading={isPending}>Save Worker</Button>
        </div>
      </form>
    </Modal>
  )
}
