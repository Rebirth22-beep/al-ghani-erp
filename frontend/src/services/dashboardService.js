import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'

export const dashboardService = {
  stats:       () => api.get(API.DASHBOARD.STATS),
  salesChart:  (params) => api.get(API.DASHBOARD.SALES_CHART, { params }),
  recentBills: () => api.get(API.DASHBOARD.RECENT_BILLS),
}
