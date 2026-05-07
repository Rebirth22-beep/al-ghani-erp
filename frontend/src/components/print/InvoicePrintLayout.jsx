import { formatCurrency } from '@/utils/formatCurrency'
import { formatDate } from '@/utils/formatDate'

export function InvoicePrintLayout({ invoice }) {
  if (!invoice) return null

  return (
    <div className="p-6 max-w-2xl mx-auto font-sans text-sm">
      <div className="text-center mb-6">
        <h1 className="text-2xl font-bold">Al-Ghani Trading</h1>
        <p className="text-gray-500">Sale Invoice</p>
      </div>

      <div className="grid grid-cols-2 gap-4 mb-6 text-sm">
        <div>
          <p><span className="font-medium">Invoice #:</span> {invoice.bill_number}</p>
          <p><span className="font-medium">Date:</span> {formatDate(invoice.date)}</p>
          <p><span className="font-medium">Party:</span> {invoice.party?.name}</p>
        </div>
        <div className="text-right">
          <p><span className="font-medium">Season:</span> {invoice.season}</p>
          <p><span className="font-medium">Payment:</span> {invoice.payment_type}</p>
          <p><span className="font-medium">Status:</span> {invoice.status}</p>
        </div>
      </div>

      <table className="w-full border-collapse text-sm mb-4">
        <thead>
          <tr className="border-b-2 border-gray-800">
            <th className="text-left py-2">Product</th>
            <th className="text-right py-2">Qty</th>
            <th className="text-right py-2">Unit</th>
            <th className="text-right py-2">Rate (Rs)</th>
            <th className="text-right py-2">Total (Rs)</th>
          </tr>
        </thead>
        <tbody>
          {(invoice.lines || []).map((line, i) => (
            <tr key={i} className="border-b border-gray-200">
              <td className="py-1">{line.product?.name}</td>
              <td className="py-1 text-right">{line.quantity}</td>
              <td className="py-1 text-right">{line.unit}</td>
              <td className="py-1 text-right">{formatCurrency(line.rate_paisas)}</td>
              <td className="py-1 text-right">{formatCurrency(line.total_paisas)}</td>
            </tr>
          ))}
        </tbody>
        <tfoot>
          <tr className="border-t-2 border-gray-800 font-bold">
            <td colSpan={4} className="py-2 text-right">Grand Total:</td>
            <td className="py-2 text-right">{formatCurrency(invoice.total_paisas)}</td>
          </tr>
        </tfoot>
      </table>

      <div className="mt-8 text-center text-xs text-gray-400">
        Thank you for your business.
      </div>
    </div>
  )
}
