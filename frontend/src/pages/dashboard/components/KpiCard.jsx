import { FadeIn } from '@/components/ui/FadeIn'
import { AppIcon } from '@/components/ui/AppIcon'

export function KpiCard({ label, value, sub, icon, color = 'brand', index = 0 }) {
  const colors = {
    brand: 'bg-brand/10 text-brand',
    blue:  'bg-blue-100 text-blue-600',
    amber: 'bg-amber-100 text-amber-600',
    red:   'bg-red-100 text-red-600',
  }

  return (
    <FadeIn delay={index * 0.08}>
      <div className="card flex items-center gap-4">
        <div className={`w-12 h-12 rounded-xl flex items-center justify-center ${colors[color]}`}>
          <AppIcon name={icon} size={24} />
        </div>
        <div>
          <p className="text-xs text-gray-500 font-medium">{label}</p>
          <p className="text-xl font-bold text-gray-900">{value ?? '-'}</p>
          {sub && <p className="text-xs text-gray-400 mt-0.5">{sub}</p>}
        </div>
      </div>
    </FadeIn>
  )
}
