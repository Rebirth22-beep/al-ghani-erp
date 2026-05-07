import { useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { useSettings, useUpdateSettings } from '../hooks/useSettings'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'

const schema = z.object({
  company_name:     z.string().min(1, 'Company name is required'),
  address:          z.string().optional(),
  phone:            z.string().optional(),
  currency_symbol:  z.string().min(1, 'Currency symbol is required'),
})

export function CompanySettingsTab() {
  const { data: settings }  = useSettings()
  const updateSettings      = useUpdateSettings()

  const { register, handleSubmit, setValue, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
    defaultValues: { currency_symbol: 'Rs' },
  })

  useEffect(() => {
    if (settings?.company) {
      setValue('company_name',    settings.company.name)
      setValue('address',         settings.company.address)
      setValue('phone',           settings.company.phone)
      setValue('currency_symbol', settings.company.currency_symbol || 'Rs')
    }
  }, [settings, setValue])

  const onSubmit = (data) => {
    updateSettings.mutate({ company: data })
  }

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4 max-w-lg">
      <Input
        label="Company Name"
        required
        placeholder="e.g. Al-Ghani Trading"
        error={errors.company_name?.message}
        {...register('company_name')}
      />
      <Input
        label="Phone"
        placeholder="e.g. 0300-1234567"
        error={errors.phone?.message}
        {...register('phone')}
      />
      <Input
        label="Address"
        placeholder="Company address"
        error={errors.address?.message}
        {...register('address')}
      />
      <Input
        label="Currency Symbol"
        placeholder="e.g. Rs"
        error={errors.currency_symbol?.message}
        {...register('currency_symbol')}
      />
      <div className="flex justify-end">
        <Button type="submit" loading={updateSettings.isPending}>Save Settings</Button>
      </div>
    </form>
  )
}
