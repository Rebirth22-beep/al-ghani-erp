import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useReportData } from './hooks/useReports'
import { SalesReportView } from './components/SalesReportView'
import { PurchaseReportView } from './components/PurchaseReportView'
import { StockReportView } from './components/StockReportView'
import { PartyReportView } from './components/PartyReportView'
import { WorkerReportView } from './components/WorkerReportView'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Input } from '@/components/ui/Input'
import { exportTablePdf } from '@/utils/exportPdf'
import { exportExcel } from '@/utils/exportExcel'

const REPORT_TYPES = [
  { value: 'sales',    label: 'Sales Report' },
  { value: 'purchase', label: 'Purchase Report' },
  { value: 'stock',    label: 'Stock Report' },
  { value: 'party',    label: 'Party Report' },
  { value: 'worker',   label: 'Worker Report' },
]

const REPORT_VIEWS = {
  sales:    SalesReportView,
  purchase: PurchaseReportView,
  stock:    StockReportView,
  party:    PartyReportView,
  worker:   WorkerReportView,
}

export function ReportsPage() {
  const [reportType, setReportType] = useState('sales')
  const [dateFrom, setDateFrom]     = useState('')
  const [dateTo, setDateTo]         = useState('')
  const [runParams, setRunParams]   = useState(null)

  const { data, isLoading } = useReportData(reportType, runParams)

  const handleRun = () => {
    setRunParams({ date_from: dateFrom, date_to: dateTo, _run: Date.now() })
  }

  const handleExportPdf = () => {
    const rows = data?.rows || []
    const label = REPORT_TYPES.find((t) => t.value === reportType)?.label || 'Report'
    exportTablePdf(label, Object.keys(rows[0] || {}), rows.map(Object.values), label.toLowerCase().replace(' ', '-'))
  }

  const handleExportExcel = () => {
    const rows = data?.rows || []
    const label = REPORT_TYPES.find((t) => t.value === reportType)?.label || 'Report'
    const headers = Object.keys(rows[0] || {})
    exportExcel(label, headers, rows.map(Object.values), label.toLowerCase().replace(' ', '-'))
  }

  const ReportView = REPORT_VIEWS[reportType]

  return (
    <AnimatedPage>
      <PageHeader title="Reports" subtitle="Generate and export business reports" />

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <Card title="Report Type">
          <div className="space-y-1">
            {REPORT_TYPES.map((t) => (
              <button
                key={t.value}
                onClick={() => { setReportType(t.value); setRunParams(null) }}
                className={`w-full text-left px-3 py-2 rounded-lg text-sm transition-colors ${
                  reportType === t.value
                    ? 'bg-brand text-white font-medium'
                    : 'hover:bg-gray-100 text-gray-700'
                }`}
              >
                {t.label}
              </button>
            ))}
          </div>
        </Card>

        <div className="lg:col-span-3 space-y-4">
          <Card title="Filters">
            <div className="flex flex-wrap gap-4 items-end">
              <Input
                label="From Date"
                type="date"
                value={dateFrom}
                onChange={(e) => setDateFrom(e.target.value)}
              />
              <Input
                label="To Date"
                type="date"
                value={dateTo}
                onChange={(e) => setDateTo(e.target.value)}
              />
              <Button onClick={handleRun}>Run Report</Button>
              {data && (
                <>
                  <Button variant="secondary" onClick={handleExportPdf}>Export PDF</Button>
                  <Button variant="secondary" onClick={handleExportExcel}>Export Excel</Button>
                </>
              )}
            </div>
          </Card>

          {(runParams || isLoading) && (
            <Card title={REPORT_TYPES.find((t) => t.value === reportType)?.label}>
              <ReportView data={data} loading={isLoading} />
            </Card>
          )}
        </div>
      </div>
    </AnimatedPage>
  )
}
