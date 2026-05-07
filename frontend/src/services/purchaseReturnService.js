import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const purchaseReturnService = createCRUDService(API.PURCHASE_RETURNS)
