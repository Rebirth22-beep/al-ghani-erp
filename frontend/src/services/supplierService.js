import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const supplierService = {
  ...createCRUDService(API.SUPPLIERS),
  ledger: (id, params) => api.get(`${API.SUPPLIERS}/${id}/ledger`, { params }),
}
