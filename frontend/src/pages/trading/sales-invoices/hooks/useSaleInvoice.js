import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { saleInvoiceService } from '@/services/saleInvoiceService'
import { useToast } from '@/hooks/useToast'

export function useSaleInvoiceList(params) {
  return useQuery({
    queryKey: ['sale-invoices', params],
    queryFn:  () => saleInvoiceService.list(params).then((r) => r.data),
  })
}

export function useSaleInvoice(id) {
  return useQuery({
    queryKey: ['sale-invoices', id],
    queryFn:  () => saleInvoiceService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function useSaleInvoiceMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['sale-invoices'] })

  const create = useMutation({
    mutationFn: saleInvoiceService.create,
    onSuccess:  () => { toast.success('Invoice created.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create invoice.'),
  })

  const update = useMutation({
    mutationFn: ({ id, data }) => saleInvoiceService.update(id, data),
    onSuccess:  () => { toast.success('Invoice updated.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to update invoice.'),
  })

  const remove = useMutation({
    mutationFn: saleInvoiceService.delete,
    onSuccess:  () => { toast.success('Invoice deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete invoice.'),
  })

  const post = useMutation({
    mutationFn: saleInvoiceService.post,
    onSuccess:  () => { toast.success('Invoice posted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to post invoice.'),
  })

  return { create, update, remove, post }
}
