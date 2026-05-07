import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { partnerService } from '@/services/partnerService'
import { useToast } from '@/hooks/useToast'

export function usePartnerList(params) {
  return useQuery({
    queryKey: ['partners', params],
    queryFn:  () => partnerService.list(params).then((r) => r.data),
  })
}

export function usePartner(id) {
  return useQuery({
    queryKey: ['partners', id],
    queryFn:  () => partnerService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function usePartnerMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['partners'] })

  const create = useMutation({
    mutationFn: partnerService.create,
    onSuccess:  () => { toast.success('Partner added.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to add partner.'),
  })

  const update = useMutation({
    mutationFn: ({ id, data }) => partnerService.update(id, data),
    onSuccess:  () => { toast.success('Partner updated.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to update partner.'),
  })

  const remove = useMutation({
    mutationFn: partnerService.delete,
    onSuccess:  () => { toast.success('Partner deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete partner.'),
  })

  return { create, update, remove }
}
