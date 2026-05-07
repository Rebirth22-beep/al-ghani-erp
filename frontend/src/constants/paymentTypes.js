export const PAYMENT_TYPES = {
  CASH:   'cash',
  CREDIT: 'credit',
}

export const PAYMENT_TYPE_LABELS = {
  [PAYMENT_TYPES.CASH]:   'Cash',
  [PAYMENT_TYPES.CREDIT]: 'Credit',
}

export const PAYMENT_TYPE_OPTIONS = Object.entries(PAYMENT_TYPE_LABELS).map(([value, label]) => ({ value, label }))
