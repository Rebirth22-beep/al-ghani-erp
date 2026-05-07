import { useFieldArray, Controller } from 'react-hook-form'
import { Button } from '@/components/ui/Button'
import { AppIcon } from '@/components/ui/AppIcon'
import { SearchProductInput } from '@/components/forms/SearchProductInput'
import { formatCurrency, toPaisas, toRupees } from '@/utils/formatCurrency'

export function PurchaseLineTable({ control, register, setValue, watch }) {
  const { fields, append, remove } = useFieldArray({ control, name: 'lines' })

  const addLine = () =>
    append({ product: null, product_id: '', quantity: 1, unit: '', cost_rate_rupees: 0, cost_rate_paisas: 0, total_paisas: 0 })

  const recalcTotal = (index) => {
    const qty  = parseFloat(watch(`lines.${index}.quantity`) || 0)
    const rate = parseFloat(watch(`lines.${index}.cost_rate_rupees`) || 0)
    const total = toPaisas(qty * rate)
    setValue(`lines.${index}.cost_rate_paisas`, toPaisas(rate))
    setValue(`lines.${index}.total_paisas`, total)
  }

  return (
    <div className="space-y-2">
      <div className="overflow-x-auto rounded-xl border border-gray-200">
        <table className="w-full text-sm">
          <thead>
            <tr>
              {['Product', 'Qty', 'Unit', 'Cost Rate (Rs)', 'Total (Rs)', ''].map((h) => (
                <th key={h} className="table-th">{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {fields.map((field, index) => (
              <tr key={field.id}>
                <td className="table-td w-56">
                  <Controller
                    name={`lines.${index}.product`}
                    control={control}
                    render={({ field: f }) => (
                      <SearchProductInput
                        value={f.value}
                        onChange={(p) => {
                          f.onChange(p)
                          setValue(`lines.${index}.product_id`,        p.id)
                          setValue(`lines.${index}.unit`,              p.unit)
                          setValue(`lines.${index}.cost_rate_rupees`,  toRupees(p.purchase_rate_paisas || 0))
                          recalcTotal(index)
                        }}
                      />
                    )}
                  />
                </td>
                <td className="table-td w-24">
                  <input
                    type="number"
                    className="form-input"
                    min="0.001"
                    step="0.001"
                    {...register(`lines.${index}.quantity`)}
                    onBlur={() => recalcTotal(index)}
                  />
                </td>
                <td className="table-td w-20">
                  <input className="form-input" {...register(`lines.${index}.unit`)} />
                </td>
                <td className="table-td w-32">
                  <input
                    type="number"
                    className="form-input"
                    step="0.01"
                    {...register(`lines.${index}.cost_rate_rupees`)}
                    onBlur={() => recalcTotal(index)}
                  />
                </td>
                <td className="table-td w-28 font-medium">
                  {formatCurrency(watch(`lines.${index}.total_paisas`) || 0)}
                </td>
                <td className="table-td w-10">
                  <button
                    type="button"
                    onClick={() => remove(index)}
                    aria-label="Remove line"
                    className="inline-flex items-center justify-center text-red-400 hover:text-red-600 transition-colors"
                  >
                    <AppIcon name="delete" size={16} />
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <Button type="button" variant="secondary" size="sm" onClick={addLine}>
        <AppIcon name="plus" size={14} />
        Add Line
      </Button>
    </div>
  )
}
