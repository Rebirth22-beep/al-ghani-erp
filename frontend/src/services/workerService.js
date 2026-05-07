import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'
import { createCRUDService } from './createCRUDService'

export const workerService = {
  ...createCRUDService(API.WORKERS),
  attendance:   (params)   => api.get(API.WORKER_ATTENDANCE, { params }),
  markAttendance: (data)   => api.post(API.WORKER_ATTENDANCE, data),
  salary:       (params)   => api.get(API.WORKER_SALARY, { params }),
  paySalary:    (data)     => api.post(API.WORKER_SALARY, data),
}
