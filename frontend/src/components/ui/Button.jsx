import { motion } from 'motion/react'
import { Spinner } from './Spinner'

export function Button({
  children,
  variant = 'primary',
  size = 'md',
  loading = false,
  disabled = false,
  type = 'button',
  className = '',
  onClick,
  ...props
}) {
  const base = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed'

  const variants = {
    primary:   'bg-brand text-white hover:bg-brand-dark focus:ring-brand',
    secondary: 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-brand',
    danger:    'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    ghost:     'text-gray-600 hover:bg-gray-100 focus:ring-gray-400',
  }

  const sizes = {
    sm:  'px-3 py-1.5 text-xs gap-1.5',
    md:  'px-4 py-2 text-sm gap-2',
    lg:  'px-5 py-2.5 text-base gap-2',
  }

  return (
    <motion.button
      type={type}
      disabled={disabled || loading}
      onClick={onClick}
      className={`${base} ${variants[variant]} ${sizes[size]} ${className}`}
      whileTap={disabled || loading ? undefined : { scale: 0.97 }}
      whileHover={disabled || loading ? undefined : { scale: 1.02 }}
      transition={{ duration: 0.15 }}
      {...props}
    >
      {loading && <Spinner size="sm" className="text-current" />}
      {children}
    </motion.button>
  )
}
