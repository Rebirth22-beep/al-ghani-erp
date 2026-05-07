import { API } from '@/constants/apiEndpoints'
import { createListService } from './createCRUDService'

export const stockAlertService = createListService(API.STOCK_ALERTS)
