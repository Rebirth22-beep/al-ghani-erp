// Monetary values are stored as integers (paisas) in DB.
// Convert to Rs for display: divide by 100.

export function formatCurrency(paisas, symbol = 'Rs') {
  if (paisas == null) return `${symbol} 0.00`
  const amount = paisas / 100
  return `${symbol} ${amount.toLocaleString('en-PK', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}

export function toPaisas(rupees) {
  return Math.round(parseFloat(rupees || 0) * 100)
}

export function toRupees(paisas) {
  return (paisas || 0) / 100
}
