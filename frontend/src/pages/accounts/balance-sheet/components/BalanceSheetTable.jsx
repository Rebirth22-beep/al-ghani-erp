import { formatCurrency } from '@/utils/formatCurrency'

function Section({ title, rows, total, totalClass }) {
  return (
    <div className="mb-4">
      <h4 className="font-semibold text-gray-700 border-b border-gray-200 pb-1 mb-2">{title}</h4>
      <table className="w-full text-sm">
        <tbody>
          {(rows || []).map((row, i) => (
            <tr key={i} className="border-b border-gray-100">
              <td className="py-2 text-gray-600 pl-4">{row.account}</td>
              <td className="py-2 text-right font-medium">{formatCurrency(row.amount)}</td>
            </tr>
          ))}
        </tbody>
        <tfoot>
          <tr>
            <td className="py-2 font-semibold">Total {title}</td>
            <td className={`py-2 text-right font-bold ${totalClass}`}>{formatCurrency(total)}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  )
}

export function BalanceSheetTable({ data, loading }) {
  if (loading) return <div className="py-8 text-center text-gray-400">Loading...</div>
  if (!data)   return null

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <Section
          title="Assets"
          rows={data.assets}
          total={data.total_assets}
          totalClass="text-blue-700"
        />
      </div>
      <div>
        <Section
          title="Liabilities"
          rows={data.liabilities}
          total={data.total_liabilities}
          totalClass="text-red-600"
        />
        <Section
          title="Equity"
          rows={data.equity}
          total={data.total_equity}
          totalClass="text-green-700"
        />
        <div className="border-t-2 border-gray-400 pt-2 flex justify-between font-bold">
          <span>Total Liabilities + Equity</span>
          <span className="text-gray-900">{formatCurrency((data.total_liabilities || 0) + (data.total_equity || 0))}</span>
        </div>
      </div>
    </div>
  )
}
