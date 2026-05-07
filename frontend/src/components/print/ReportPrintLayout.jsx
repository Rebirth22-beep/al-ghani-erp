import { formatDate } from '@/utils/formatDate'

export function ReportPrintLayout({ title, dateFrom, dateTo, columns, rows }) {
  return (
    <div className="p-6 max-w-4xl mx-auto font-sans text-sm">
      <div className="text-center mb-6">
        <h1 className="text-2xl font-bold">Al-Ghani Trading</h1>
        <h2 className="text-lg font-medium text-gray-700">{title}</h2>
        {(dateFrom || dateTo) && (
          <p className="text-sm text-gray-500">
            Period: {dateFrom ? formatDate(dateFrom) : '—'} to {dateTo ? formatDate(dateTo) : '—'}
          </p>
        )}
      </div>

      <table className="w-full border-collapse text-sm">
        <thead>
          <tr className="border-b-2 border-gray-800">
            {columns.map((col) => (
              <th key={col} className="text-left py-2 pr-4">{col}</th>
            ))}
          </tr>
        </thead>
        <tbody>
          {rows.map((row, i) => (
            <tr key={i} className={`border-b border-gray-200 ${i % 2 === 0 ? 'bg-gray-50' : ''}`}>
              {row.map((cell, j) => (
                <td key={j} className="py-1.5 pr-4">{cell}</td>
              ))}
            </tr>
          ))}
          {rows.length === 0 && (
            <tr>
              <td colSpan={columns.length} className="py-4 text-center text-gray-400">No data available.</td>
            </tr>
          )}
        </tbody>
      </table>

      <div className="mt-6 text-right text-xs text-gray-400">
        Printed on {formatDate(new Date().toISOString())}
      </div>
    </div>
  )
}
