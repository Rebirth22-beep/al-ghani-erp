import { format, parseISO, isValid } from 'date-fns'

export function formatDate(dateStr, pattern = 'dd MMM yyyy') {
  if (!dateStr) return '—'
  const date = typeof dateStr === 'string' ? parseISO(dateStr) : dateStr
  return isValid(date) ? format(date, pattern) : '—'
}

export function formatDateTime(dateStr) {
  return formatDate(dateStr, 'dd MMM yyyy, hh:mm a')
}

export function toApiDate(date) {
  if (!date) return null
  const d = typeof date === 'string' ? parseISO(date) : date
  return isValid(d) ? format(d, 'yyyy-MM-dd') : null
}
