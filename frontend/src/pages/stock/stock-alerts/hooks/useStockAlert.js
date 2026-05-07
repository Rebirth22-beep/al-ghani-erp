import { useQuery } from '@tanstack/react-query'
import { stockAlertService } from '@/services/stockAlertService'

export function useStockAlerts() {
  return useQuery({
    queryKey: ['stock-alerts'],
    queryFn:  () => stockAlertService.list().then((r) => r.data),
  })
}
