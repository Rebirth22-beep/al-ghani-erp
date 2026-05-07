import api from '@/config/axios'
import { API } from '@/constants/apiEndpoints'

export const authService = {
  login:  (credentials) => api.post(API.AUTH.LOGIN, credentials),
  logout: ()            => api.post(API.AUTH.LOGOUT),
  me:     ()            => api.get(API.AUTH.ME),
  forgotPassword: (email) => api.post(API.AUTH.FORGOT_PASSWORD, { email }),
}
