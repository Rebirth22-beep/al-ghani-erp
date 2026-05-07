import { Badge } from '@/components/ui/Badge'
import { AnimatedList } from '@/components/ui/AnimatedList'

export function StockAlertWidget({ alerts = [] }) {
  if (!alerts.length) {
    return <p className="text-sm text-gray-400">No stock alerts.</p>
  }

  return (
    <AnimatedList
      items={alerts.slice(0, 5)}
      keyExtractor={(alert) => alert.id}
      className="space-y-2"
      renderItem={(alert) => (
        <div className="flex items-center justify-between text-sm">
          <span className="text-gray-800 font-medium truncate">{alert.product_name}</span>
          <Badge variant="danger">{alert.stock} left</Badge>
        </div>
      )}
    />
  )
}
