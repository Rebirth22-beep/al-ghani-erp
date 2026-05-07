import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { purchaseReturnService } from '@/services/purchaseReturnService'
import { useToast } from '@/hooks/useToast'

export function usePurchaseReturnList(params) {
  return useQuery({
    queryKey: ['purchase-returns', params],
    queryFn:  () => purchaseReturnService.list(params).then((r) => r.data),
  })
}

export function usePurchaseReturn(id) {
  return useQuery({
    queryKey: ['purchase-returns', id],
    queryFn:  () => purchaseReturnService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function usePurchaseReturnMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['purchase-returns'] })

  const create = useMutation({
    mutationFn: purchaseReturnService.create,
    onSuccess:  () => { toast.success('Purchase return created.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create purchase return.'),
  })

  const remove = useMutation({
    mutationFn: purchaseReturnService.delete,
    onSuccess:  () => { toast.success('Purchase return deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete purchase return.'),
  })

  return { create, remove }
}
