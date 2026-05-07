import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const purchaseEntryService = {
  ...createCRUDService(API.PURCHASE_ENTRIES),
  post: (id) => api.post(`${API.PURCHASE_ENTRIES}/${id}/post`),
}
