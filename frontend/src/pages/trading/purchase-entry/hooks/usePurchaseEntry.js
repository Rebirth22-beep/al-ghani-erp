import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { purchaseEntryService } from '@/services/purchaseEntryService'
import { useToast } from '@/hooks/useToast'

export function usePurchaseEntryList(params) {
  return useQuery({
    queryKey: ['purchase-entries', params],
    queryFn:  () => purchaseEntryService.list(params).then((r) => r.data),
  })
}

export function usePurchaseEntry(id) {
  return useQuery({
    queryKey: ['purchase-entries', id],
    queryFn:  () => purchaseEntryService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function usePurchaseEntryMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['purchase-entries'] })

  const create = useMutation({
    mutationFn: purchaseEntryService.create,
    onSuccess:  () => { toast.success('Purchase entry created.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create purchase entry.'),
  })

  const update = useMutation({
    mutationFn: ({ id, data }) => purchaseEntryService.update(id, data),
    onSuccess:  () => { toast.success('Purchase entry updated.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to update purchase entry.'),
  })

  const remove = useMutation({
    mutationFn: purchaseEntryService.delete,
    onSuccess:  () => { toast.success('Purchase entry deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete purchase entry.'),
  })

  const post = useMutation({
    mutationFn: purchaseEntryService.post,
    onSuccess:  () => { toast.success('Purchase entry posted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to post purchase entry.'),
  })

  return { create, update, remove, post }
}
