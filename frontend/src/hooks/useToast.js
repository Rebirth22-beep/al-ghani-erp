import { useNotificationStore } from '@/stores/notificationStore'

export function useToast() {
  const { success, error, warning, info } = useNotificationStore()
  return { success, error, warning, info }
}
