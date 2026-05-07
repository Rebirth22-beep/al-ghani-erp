import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { supplierService } from '@/services/supplierService'
import { useToast } from '@/hooks/useToast'

export function useSupplierList(params) {
  return useQuery({
    queryKey: ['suppliers', params],
    queryFn:  () => supplierService.list(params).then((r) => r.data),
  })
}

export function useSupplier(id) {
  return useQuery({
    queryKey: ['suppliers', id],
    queryFn:  () => supplierService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function useSupplierLedger(id, params) {
  return useQuery({
    queryKey: ['supplier-ledger', id, params],
    queryFn:  () => supplierService.ledger(id, params).then((r) => r.data),
    enabled:  !!id,
  })
}

export function useSupplierMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['suppliers'] })

  const create = useMutation({
    mutationFn: supplierService.create,
    onSuccess:  () => { toast.success('Supplier created.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create supplier.'),
  })

  const update = useMutation({
    mutationFn: ({ id, data }) => supplierService.update(id, data),
    onSuccess:  () => { toast.success('Supplier updated.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to update supplier.'),
  })

  const remove = useMutation({
    mutationFn: supplierService.delete,
    onSuccess:  () => { toast.success('Supplier deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete supplier.'),
  })

  return { create, update, remove }
}
