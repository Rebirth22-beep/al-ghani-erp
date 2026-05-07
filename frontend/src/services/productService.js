import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const productService = createCRUDService(API.PRODUCTS)
