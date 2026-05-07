import { useQuery } from '@tanstack/react-query'
import { accountService } from '@/services/accountService'

export function useTrialBalance(params) {
  return useQuery({
    queryKey: ['trial-balance', params],
    queryFn:  () => accountService.trialBalance(params).then((r) => r.data),
  })
}
