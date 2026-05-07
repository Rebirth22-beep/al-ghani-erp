import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'

export const accountService = {
  generalLedger: (params) => api.get(API.GENERAL_LEDGER, { params }),
  trialBalance:  (params) => api.get(API.TRIAL_BALANCE, { params }),
  profitLoss:    (params) => api.get(API.PROFIT_LOSS, { params }),
  balanceSheet:  (params) => api.get(API.BALANCE_SHEET, { params }),
  cashFlow:      (params) => api.get(API.CASH_FLOW, { params }),
}
