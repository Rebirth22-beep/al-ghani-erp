import { useAuthStore } from '@/stores/authStore'
import { ROLES } from '@/constants/roles'

export function usePermission() {
  const user = useAuthStore((s) => s.user)

  const isAdmin      = user?.role === ROLES.ADMIN
  const isSalesman   = user?.role === ROLES.SALESMAN
  const isAccountant = user?.role === ROLES.ACCOUNTANT

  const can = (roles) => Array.isArray(roles) ? roles.includes(user?.role) : user?.role === roles

  return { isAdmin, isSalesman, isAccountant, can }
}
