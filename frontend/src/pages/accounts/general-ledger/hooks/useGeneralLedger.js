import { useQuery } from '@tanstack/react-query'
import { accountService } from '@/services/accountService'

export function useGeneralLedger(params) {
  return useQuery({
    queryKey: ['general-ledger', params],
    queryFn:  () => accountService.generalLedger(params).then((r) => r.data),
  })
}
