import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'

export const partyLedgerService = {
  list: (params) => api.get(API.PARTY_LEDGER, { params }),
}
