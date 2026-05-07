import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useDashboardData } from './hooks/useDashboardData'
import { KpiCard } from './components/KpiCard'
import { SalesChart } from './components/SalesChart'
import { StockAlertWidget } from './components/StockAlertWidget'
import { RecentBillsWidget } from './components/RecentBillsWidget'
import { Card } from '@/components/ui/Card'
import { Spinner } from '@/components/ui/Spinner'
import { WidgetError } from '@/components/ui/WidgetError'
import { PageHeader } from '@/components/layout/PageHeader'
import { formatCurrency } from '@/utils/formatCurrency'

/**
 * Dashboard page.
 *
 * Rule (project-rules-and-decisions.md Section 21): NEVER show fake fallback data.
 * Every widget either renders real data, a loading spinner, or a WidgetError with retry.
 */
export function DashboardPage() {
  const { stats, salesChart, recentBills } = useDashboardData()
  const s = stats.data

  return (
    <AnimatedPage>
      <PageHeader title="Dashboard" subtitle="Overview of today's activity" />

      {/* KPI strip */}
      <Card className="mb-6">
        {stats.isLoading && <div className="py-6 flex justify-center"><Spinner /></div>}
        {stats.isError && (
          <WidgetError
            message={stats.error?.response?.data?.message || 'Could not load dashboard stats.'}
            onRetry={() => stats.refetch()}
          />
        )}
        {stats.isSuccess && (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <KpiCard index={0} label="Today's Sales"    value={formatCurrency(s.today_sales_paisas)}    icon="sales"     color="brand" />
            <KpiCard index={1} label="Outstanding"      value={formatCurrency(s.outstanding_paisas)}    icon="clock"     color="amber" />
            <KpiCard index={2} label="Stock Alerts"     value={s.stock_alert_count ?? 0}                icon="alert"     color="red"   />
            <KpiCard index={3} label="Active Customers" value={s.active_customers ?? 0}                 icon="customers" color="blue"  />
          </div>
        )}
      </Card>

      {/* Sales chart + stock alerts */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <Card title="Monthly Sales" className="lg:col-span-2">
          {salesChart.isLoading && <div className="h-60 flex items-center justify-center"><Spinner /></div>}
          {salesChart.isError && (
            <WidgetError
              message={salesChart.error?.response?.data?.message || 'Could not load sales chart.'}
              onRetry={() => salesChart.refetch()}
            />
          )}
          {salesChart.isSuccess && <SalesChart data={salesChart.data} />}
        </Card>

        <Card title="Stock Alerts">
          {stats.isLoading && <div className="py-6 flex justify-center"><Spinner /></div>}
          {stats.isError && (
            <WidgetError
              message="Could not load stock alerts."
              onRetry={() => stats.refetch()}
            />
          )}
          {stats.isSuccess && <StockAlertWidget alerts={s.stock_alerts || []} />}
        </Card>
      </div>

      {/* Recent bills */}
      <Card title="Recent Bills" className="mt-4">
        {recentBills.isLoading && <div className="py-6 flex justify-center"><Spinner /></div>}
        {recentBills.isError && (
          <WidgetError
            message={recentBills.error?.response?.data?.message || 'Could not load recent bills.'}
            onRetry={() => recentBills.refetch()}
          />
        )}
        {recentBills.isSuccess && <RecentBillsWidget bills={recentBills.data} />}
      </Card>
    </AnimatedPage>
  )
}
