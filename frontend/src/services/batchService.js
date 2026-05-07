import { API } from '@/constants/apiEndpoints'
import { createListService } from './createCRUDService'

export const batchService = createListService(API.BATCHES)
