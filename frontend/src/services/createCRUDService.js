import api from '@/config/axios'

/**
 * Returns a standard list service for read-only endpoints.
 *
 * Use this for endpoints that only support index/list, such as stock alerts.
 */
export function createListService(endpoint) {
  return {
    list: (params) => api.get(endpoint, { params }),
  }
}

/**
 * Returns a standard CRUD service for a given API endpoint.
 *
 * Use this for normal resource endpoints with:
 * list, get, create, update, delete.
 */
export function createCRUDService(endpoint) {
  return {
    list:   (params)     => api.get(endpoint, { params }),
    get:    (id)         => api.get(`${endpoint}/${id}`),
    create: (data)       => api.post(endpoint, data),
    update: (id, data)   => api.put(`${endpoint}/${id}`, data),
    delete: (id)         => api.delete(`${endpoint}/${id}`),
  }
}
