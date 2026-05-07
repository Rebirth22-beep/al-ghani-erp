import { Controller } from 'react-hook-form'
import { Input } from '@/components/ui/Input'
import { DatePicker } from '@/components/forms/DatePicker'
import { SearchPartyInput } from '@/components/forms/SearchPartyInput'

export function PurchaseHeader({ control, register, errors, setValue }) {
  return (
    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
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

      <Input
        label="Reference / PO Number"
        placeholder="e.g. PO-2025-001"
        error={errors.reference?.message}
        {...register('reference')}
      />

      <Input
        label="Bill Book No."
        placeholder="e.g. B-001"
        error={errors.bill_book_number?.message}
        {...register('bill_book_number')}
      />
    </div>
  )
}
