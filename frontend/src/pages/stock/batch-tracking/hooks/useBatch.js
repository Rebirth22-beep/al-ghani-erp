import { useQuery } from '@tanstack/react-query'
import { batchService } from '@/services/batchService'

export function useBatchList(params) {
  return useQuery({
    queryKey: ['batches', params],
    queryFn:  () => batchService.list(params).then((r) => r.data),
  })
}
