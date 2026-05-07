import { FadeIn } from './FadeIn'

export function Card({ children, className = '', title, action, delay = 0 }) {
  return (
    <FadeIn delay={delay} className={className}>
      <div className="card bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        {(title || action) && (
          <div className="flex items-center justify-between mb-4">
            {title && <h3 className="text-base font-semibold text-gray-900">{title}</h3>}
            {action && <div>{action}</div>}
          </div>
        )}
        {children}
      </div>
    </FadeIn>
  )
}
