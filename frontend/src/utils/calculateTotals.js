import { toPaisas } from './formatCurrency'

export function calculateLineTotal(qty, rate) {
  return toPaisas(parseFloat(qty || 0) * parseFloat(rate || 0))
}

export function calculateInvoiceTotals(lines) {
  const subtotal = lines.reduce((sum, line) => sum + (line.total_paisas || 0), 0)
  return { subtotal }
}
