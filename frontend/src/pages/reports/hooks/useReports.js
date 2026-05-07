import { useQuery } from '@tanstack/react-query'
import { reportService } from '@/services/reportService'

export function useReportData(type, params) {
  const serviceMap = {
    sales:    reportService.sales,
    purchase: reportService.purchase,
    stock:    reportService.stock,
    party:    reportService.party,
    worker:   reportService.worker,
  }

  return useQuery({
    queryKey: ['reports', type, params],
    queryFn:  () => serviceMap[type]?.(params).then((r) => r.data),
    enabled:  !!type && !!params?._run,
  })
}
