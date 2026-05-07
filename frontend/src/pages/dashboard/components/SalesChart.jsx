import { AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts'
import { toRupees } from '@/utils/formatCurrency'
import { FadeIn } from '@/components/ui/FadeIn'
import { CHART_COLORS } from '@/constants/theme'

export function SalesChart({ data = [] }) {
  const formatted = data.map((d) => ({ ...d, total: toRupees(d.total_paisas) }))

  return (
    <FadeIn>
      <ResponsiveContainer width="100%" height={240}>
        <AreaChart data={formatted} margin={{ top: 4, right: 4, left: 0, bottom: 0 }}>
          <defs>
            <linearGradient id="salesGradient" x1="0" y1="0" x2="0" y2="1">
              <stop offset="5%"  stopColor={CHART_COLORS.sales} stopOpacity={0.3} />
              <stop offset="95%" stopColor={CHART_COLORS.sales} stopOpacity={0} />
            </linearGradient>
          </defs>
          <CartesianGrid strokeDasharray="3 3" stroke={CHART_COLORS.grid} />
          <XAxis dataKey="label" tick={{ fontSize: 11 }} />
          <YAxis tick={{ fontSize: 11 }} tickFormatter={(v) => `Rs ${v.toLocaleString()}`} width={80} />
          <Tooltip formatter={(v) => [`Rs ${v.toLocaleString()}`, 'Sales']} />
          <Area type="monotone" dataKey="total" stroke={CHART_COLORS.sales} fill="url(#salesGradient)" strokeWidth={2} />
        </AreaChart>
      </ResponsiveContainer>
    </FadeIn>
  )
}
