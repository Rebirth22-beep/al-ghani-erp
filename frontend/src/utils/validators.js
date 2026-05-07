import { z } from 'zod'

export const phoneSchema = z
  .string()
  .regex(/^(\+92|0)?3\d{9}$/, 'Invalid Pakistani phone number')
  .optional()
  .or(z.literal(''))

export const requiredString = (label) =>
  z.string({ required_error: `${label} is required` }).min(1, `${label} is required`)

export const positiveNumber = (label) =>
  z.coerce.number({ required_error: `${label} is required` }).positive(`${label} must be positive`)

export const nonNegativeNumber = (label) =>
  z.coerce.number({ required_error: `${label} is required` }).min(0, `${label} must be 0 or more`)
