import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { productService } from '@/services/productService'
import { useToast } from '@/hooks/useToast'

export function useProductList(params) {
  return useQuery({
    queryKey: ['products', params],
    queryFn:  () => productService.list(params).then((r) => r.data),
  })
}

export function useProduct(id) {
  return useQuery({
    queryKey: ['products', id],
    queryFn:  () => productService.get(id).then((r) => r.data.data),
    enabled:  !!id,
  })
}

export function useProductMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['products'] })

  const create = useMutation({
    mutationFn: productService.create,
    onSuccess:  () => { toast.success('Product created.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to create product.'),
  })

  const update = useMutation({
    mutationFn: ({ id, data }) => productService.update(id, data),
    onSuccess:  () => { toast.success('Product updated.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to update product.'),
  })

  const remove = useMutation({
    mutationFn: productService.delete,
    onSuccess:  () => { toast.success('Product deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete product.'),
  })

  return { create, update, remove }
}
