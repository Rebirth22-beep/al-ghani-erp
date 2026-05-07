import { Navigate } from 'react-router-dom'
import { usePermission } from '@/hooks/usePermission'
import { ROUTES } from '@/constants/routes'

export function RoleGuard({ children, roles }) {
  const { can } = usePermission()

  if (!can(roles)) {
    return <Navigate to={ROUTES.DASHBOARD} replace />
  }

  return children
}
