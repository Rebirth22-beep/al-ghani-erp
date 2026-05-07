import { useQuery } from '@tanstack/react-query'
import { dashboardService } from '@/services/dashboardService'

export function useDashboardData() {
  const stats = useQuery({
    queryKey: ['dashboard', 'stats'],
    queryFn:  () => dashboardService.stats().then((r) => r.data.data),
  })

  const salesChart = useQuery({
    queryKey: ['dashboard', 'sales-chart'],
    queryFn:  () => dashboardService.salesChart().then((r) => r.data.data),
  })

  const recentBills = useQuery({
    queryKey: ['dashboard', 'recent-bills'],
    queryFn:  () => dashboardService.recentBills().then((r) => r.data.data),
  })

  return { stats, salesChart, recentBills }
}
