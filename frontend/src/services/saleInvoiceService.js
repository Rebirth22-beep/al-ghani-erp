import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const saleInvoiceService = {
  ...createCRUDService(API.SALE_INVOICES),
  post:      (id)      => api.post(`${API.SALE_INVOICES}/${id}/post`),
  partyWise: (partyId) => api.get(`${API.SALE_INVOICES}/party/${partyId}`),
}
