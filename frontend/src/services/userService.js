import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const userService = createCRUDService(API.USERS)
