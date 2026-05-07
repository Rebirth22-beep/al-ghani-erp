import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const customerService = {
  ...createCRUDService(API.CUSTOMERS),
  ledger: (id, params) => api.get(`${API.CUSTOMERS}/${id}/ledger`, { params }),
}
