import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { customerService } from '@/services/customerService'
import { useToast } from '@/hooks/useToast'

export function useCustomerList(params) {
  return useQuery({
    queryKey: ['customers', params],
    queryFn:  () => customerService.list(params).then((r) => r.data),
  })
}

export function useCustomer(id) {
  return useQuery({
    queryKey: ['customers', id],
    queryFn:  () => customerService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function useCustomerLedger(id, params) {
  return useQuery({
    queryKey: ['customer-ledger', id, params],
    queryFn:  () => customerService.ledger(id, params).then((r) => r.data),
    enabled:  !!id,
  })
}

export function useCustomerMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['customers'] })

  const create = useMutation({
    mutationFn: customerService.create,
    onSuccess:  () => { toast.success('Customer created.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create customer.'),
  })

  const update = useMutation({
    mutationFn: ({ id, data }) => customerService.update(id, data),
    onSuccess:  () => { toast.success('Customer updated.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to update customer.'),
  })

  const remove = useMutation({
    mutationFn: customerService.delete,
    onSuccess:  () => { toast.success('Customer deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete customer.'),
  })

  return { create, update, remove }
}
