import { formatCurrency } from '@/utils/formatCurrency'

export function InvoiceFooter({ totalPaisas }) {
  return (
    <div className="flex justify-end">
      <div className="w-64 space-y-2 text-sm">
        <div className="flex justify-between font-bold text-base pt-2 border-t border-gray-300">
          <span>Total</span>
          <span>{formatCurrency(totalPaisas)}</span>
        </div>
      </div>
    </div>
  )
}
