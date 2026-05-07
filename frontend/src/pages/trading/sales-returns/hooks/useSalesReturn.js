import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { salesReturnService } from '@/services/salesReturnService'
import { useToast } from '@/hooks/useToast'

export function useSalesReturnList(params) {
  return useQuery({
    queryKey: ['sales-returns', params],
    queryFn:  () => salesReturnService.list(params).then((r) => r.data),
  })
}

export function useSalesReturn(id) {
  return useQuery({
    queryKey: ['sales-returns', id],
    queryFn:  () => salesReturnService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function useSalesReturnMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['sales-returns'] })

  const create = useMutation({
    mutationFn: salesReturnService.create,
    onSuccess:  () => { toast.success('Sales return created.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create sales return.'),
  })

  const remove = useMutation({
    mutationFn: salesReturnService.delete,
    onSuccess:  () => { toast.success('Sales return deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete sales return.'),
  })

  return { create, remove }
}
