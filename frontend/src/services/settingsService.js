import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'

/**
 * Settings are a singleton resource, so they do not fit normal CRUD.
 * Keep the endpoint calls centralized here instead of calling api directly in hooks.
 */
export const settingsService = {
  get:    ()     => api.get(API.SETTINGS),
  update: (data) => api.put(API.SETTINGS, data),
}
