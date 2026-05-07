import { useAuthStore } from '@/stores/authStore'

export function useAuth() {
  const user  = useAuthStore((s) => s.user)
  const token = useAuthStore((s) => s.token)
  const setAuth = useAuthStore((s) => s.setAuth)
  const logout  = useAuthStore((s) => s.logout)

  return {
    user,
    token,
    isAuthenticated: !!token,
    setAuth,
    logout,
  }
}
