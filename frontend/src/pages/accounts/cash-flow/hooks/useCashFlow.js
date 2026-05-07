import { useQuery } from '@tanstack/react-query'
import { accountService } from '@/services/accountService'

export function useCashFlow(params) {
  return useQuery({
    queryKey: ['cash-flow', params],
    queryFn:  () => accountService.cashFlow(params).then((r) => r.data),
  })
}
