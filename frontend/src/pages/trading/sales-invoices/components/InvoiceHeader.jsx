import { Controller } from 'react-hook-form'
import { Input } from '@/components/ui/Input'
import { Select } from '@/components/ui/Select'
import { DatePicker } from '@/components/forms/DatePicker'
import { SeasonToggle } from '@/components/forms/SeasonToggle'
import { SearchPartyInput } from '@/components/forms/SearchPartyInput'
import { PAYMENT_TYPE_OPTIONS } from '@/constants/paymentTypes'

export function InvoiceHeader({ control, register, errors, setValue }) {
  return (
    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <Input
        label="Bill Number"
        placeholder="Auto"
        disabled
        {...register('bill_number')}
      />

      <DatePicker
        label="Date"
        required
        error={errors.date?.message}
        {...register('date')}
      />

      <div className="flex flex-col gap-1">
        <label className="form-label">Party <span className="text-red-500">*</span></label>
        <Controller
          name="party"
          control={control}
          render={({ field }) => (
            <SearchPartyInput
              type="customer"
              value={field.value}
              onChange={(p) => { field.onChange(p); setValue('party_id', p.id) }}
            />
          )}
        />
        {errors.party_id && <p className="text-xs text-red-600">{errors.party_id.message}</p>}
      </div>

      <Select
        label="Payment Type"
        required
        options={PAYMENT_TYPE_OPTIONS}
        error={errors.payment_type?.message}
        {...register('payment_type')}
      />

      <div className="flex flex-col gap-1">
        <label className="form-label">Season</label>
        <Controller
          name="season"
          control={control}
          render={({ field }) => <SeasonToggle value={field.value} onChange={field.onChange} />}
        />
      </div>

      <Input
        label="Bill Book No."
        placeholder="e.g. B-001"
        error={errors.bill_book_number?.message}
        {...register('bill_book_number')}
      />
    </div>
  )
}
