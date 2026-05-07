import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { stockAdjustmentService } from '@/services/stockAdjustmentService'
import { useToast } from '@/hooks/useToast'

export function useStockAdjustmentList(params) {
  return useQuery({
    queryKey: ['stock-adjustments', params],
    queryFn:  () => stockAdjustmentService.list(params).then((r) => r.data),
  })
}

export function useStockAdjustmentMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['stock-adjustments'] })

  const create = useMutation({
    mutationFn: stockAdjustmentService.create,
    onSuccess:  () => { toast.success('Adjustment saved.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to save adjustment.'),
  })

  const remove = useMutation({
    mutationFn: stockAdjustmentService.delete,
    onSuccess:  () => { toast.success('Adjustment deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete adjustment.'),
  })

  return { create, remove }
}
