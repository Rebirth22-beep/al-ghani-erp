import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/hooks/useToast'
import { settingsService } from '@/services/settingsService'
import { userService } from '@/services/userService'

export function useSettings() {
  return useQuery({
    queryKey: ['settings'],
    queryFn:  () => settingsService.get().then((r) => r.data),
  })
}

export function useUpdateSettings() {
  const qc    = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: settingsService.update,
    onSuccess:  () => { toast.success('Settings saved.'); qc.invalidateQueries({ queryKey: ['settings'] }) },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to save settings.'),
  })
}

export function useUsers() {
  return useQuery({
    queryKey: ['users'],
    queryFn:  () => userService.list().then((r) => r.data),
  })
}

export function useCreateUser() {
  const qc    = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: userService.create,
    onSuccess:  () => { toast.success('User created.'); qc.invalidateQueries({ queryKey: ['users'] }) },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create user.'),
  })
}
