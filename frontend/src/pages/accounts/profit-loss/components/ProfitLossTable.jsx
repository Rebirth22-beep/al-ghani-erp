import { formatCurrency } from '@/utils/formatCurrency'

function Section({ title, rows, total, totalLabel, totalClass }) {
  return (
    <div className="mb-4">
      <h4 className="font-semibold text-gray-700 border-b border-gray-200 pb-1 mb-2">{title}</h4>
      <table className="w-full text-sm">
        <tbody>
          {(rows || []).map((row, i) => (
            <tr key={i} className="border-b border-gray-100">
              <td className="py-2 text-gray-600">{row.account}</td>
              <td className="py-2 text-right font-medium">{formatCurrency(row.amount)}</td>
            </tr>
          ))}
        </tbody>
        <tfoot>
          <tr>
            <td className="py-2 font-semibold">{totalLabel}</td>
            <td className={`py-2 text-right font-bold ${totalClass}`}>{formatCurrency(total)}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  )
}

export function ProfitLossTable({ data, loading }) {
  if (loading) return <div className="py-8 text-center text-gray-400">Loading...</div>
  if (!data)   return null

  const net    = (data.total_revenue || 0) - (data.total_expenses || 0)
  const isProfit = net >= 0

  return (
    <div className="space-y-4">
      <Section
        title="Revenue"
        rows={data.revenue}
        total={data.total_revenue}
        totalLabel="Total Revenue"
        totalClass="text-green-700"
      />
      <Section
        title="Expenses"
        rows={data.expenses}
        total={data.total_expenses}
        totalLabel="Total Expenses"
        totalClass="text-red-600"
      />
      <div className={`flex justify-between items-center p-4 rounded-lg font-bold text-lg ${isProfit ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'}`}>
        <span>{isProfit ? 'Net Profit' : 'Net Loss'}</span>
        <span>{formatCurrency(Math.abs(net))}</span>
      </div>
    </div>
  )
}
