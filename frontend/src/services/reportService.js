import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'

export const reportService = {
  sales:    (params) => api.get(`${API.REPORTS}/sales`, { params }),
  purchase: (params) => api.get(`${API.REPORTS}/purchase`, { params }),
  stock:    (params) => api.get(`${API.REPORTS}/stock`, { params }),
  party:    (params) => api.get(`${API.REPORTS}/party`, { params }),
  worker:   (params) => api.get(`${API.REPORTS}/worker`, { params }),
}
