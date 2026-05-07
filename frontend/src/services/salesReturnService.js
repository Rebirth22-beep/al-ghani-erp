import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const salesReturnService = createCRUDService(API.SALES_RETURNS)
