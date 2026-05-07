import { useQuery } from '@tanstack/react-query'
import { accountService } from '@/services/accountService'

export function useBalanceSheet(params) {
  return useQuery({
    queryKey: ['balance-sheet', params],
    queryFn:  () => accountService.balanceSheet(params).then((r) => r.data),
  })
}
