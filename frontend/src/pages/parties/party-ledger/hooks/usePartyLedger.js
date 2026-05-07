import { useQuery } from '@tanstack/react-query'
import { partyLedgerService } from '@/services/partyLedgerService'

export function usePartyLedger(params) {
  return useQuery({
    queryKey: ['party-ledger', params],
    queryFn:  () => partyLedgerService.list(params).then((r) => r.data),
  })
}
