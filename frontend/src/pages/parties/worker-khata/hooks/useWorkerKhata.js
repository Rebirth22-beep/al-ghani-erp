import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { workerService } from '@/services/workerService'
import { useToast } from '@/hooks/useToast'

export function useWorkerList(params) {
  return useQuery({
    queryKey: ['workers', params],
    queryFn:  () => workerService.list(params).then((r) => r.data),
  })
}

export function useWorkerMutations() {
  const qc    = useQueryClient()
  const toast = useToast()

  const invalidate = () => qc.invalidateQueries({ queryKey: ['workers'] })

  const create = useMutation({
    mutationFn: workerService.create,
    onSuccess:  () => { toast.success('Worker added.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to add worker.'),
  })

  const update = useMutation({
    mutationFn: ({ id, data }) => workerService.update(id, data),
    onSuccess:  () => { toast.success('Worker updated.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to update worker.'),
  })

  const remove = useMutation({
    mutationFn: workerService.delete,
    onSuccess:  () => { toast.success('Worker deleted.'); invalidate() },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to delete worker.'),
  })

  return { create, update, remove }
}

export function useAttendanceList(params) {
  return useQuery({
    queryKey: ['worker-attendance', params],
    queryFn:  () => workerService.attendance(params).then((r) => r.data),
  })
}

export function useMarkAttendance() {
  const qc    = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: workerService.markAttendance,
    onSuccess:  () => { toast.success('Attendance marked.'); qc.invalidateQueries({ queryKey: ['worker-attendance'] }) },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to mark attendance.'),
  })
}

export function useSalaryList(params) {
  return useQuery({
    queryKey: ['worker-salary', params],
    queryFn:  () => workerService.salary(params).then((r) => r.data),
  })
}

export function usePaySalary() {
  const qc    = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: workerService.paySalary,
    onSuccess:  () => { toast.success('Salary paid.'); qc.invalidateQueries({ queryKey: ['worker-salary'] }) },
    onError:    (e) => toast.error(e.response?.data?.message || 'Failed to pay salary.'),
  })
}
