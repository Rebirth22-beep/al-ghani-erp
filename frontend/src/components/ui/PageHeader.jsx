import { FadeIn } from './FadeIn'

export function PageHeader({ title, subtitle, actions }) {
  return (
    <FadeIn className="flex items-start justify-between mb-6">
      <div>
        <h1 className="text-xl font-semibold text-gray-900">{title}</h1>
        {subtitle && <p className="mt-1 text-sm text-gray-500">{subtitle}</p>}
      </div>
      {actions && <div className="flex items-center gap-3">{actions}</div>}
    </FadeIn>
  )
}
