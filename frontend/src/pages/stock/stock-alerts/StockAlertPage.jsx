import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { useStockAlerts } from './hooks/useStockAlert'
import { StockAlertTable } from './components/StockAlertTable'
import { PageHeader } from '@/components/layout/PageHeader'
import { Button } from '@/components/ui/Button'
import { useQueryClient } from '@tanstack/react-query'

export function StockAlertPage() {
  const { data, isLoading } = useStockAlerts()
  const qc                  = useQueryClient()

  const refresh = () => qc.invalidateQueries({ queryKey: ['stock-alerts'] })

  return (
    <AnimatedPage>
      <PageHeader
        title="Stock Alerts"
        subtitle="Products below minimum stock level"
        actions={
          <Button variant="secondary" onClick={refresh}>Refresh</Button>
        }
      />

      <StockAlertTable data={data?.data} loading={isLoading} />
    </AnimatedPage>
  )
}
