import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const partnerService = {
  ...createCRUDService(API.PARTNERS),
  ledger: (id, params) => api.get(`${API.PARTNERS}/${id}/ledger`, { params }),
}
