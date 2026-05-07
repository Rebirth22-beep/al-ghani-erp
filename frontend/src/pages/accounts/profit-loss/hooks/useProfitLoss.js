import { useQuery } from '@tanstack/react-query'
import { accountService } from '@/services/accountService'

export function useProfitLoss(params) {
  return useQuery({
    queryKey: ['profit-loss', params],
    queryFn:  () => accountService.profitLoss(params).then((r) => r.data),
  })
}
