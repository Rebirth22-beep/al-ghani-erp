import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

const stockAdjustmentCrud = createCRUDService(API.STOCK_ADJUSTMENTS)

export const stockAdjustmentService = {
  list:   stockAdjustmentCrud.list,
  get:    stockAdjustmentCrud.get,
  create: stockAdjustmentCrud.create,
  delete: stockAdjustmentCrud.delete,
}
