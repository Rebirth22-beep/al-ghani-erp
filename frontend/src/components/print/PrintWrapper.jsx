import { usePrint } from '@/hooks/usePrint'
import { Button } from '@/components/ui/Button'

export function PrintWrapper({ id, children }) {
  const { print } = usePrint()

  return (
    <div>
      <div className="no-print mb-4">
        <Button variant="secondary" size="sm" onClick={() => print(id)}>Print</Button>
      </div>
      <div id={id}>{children}</div>
    </div>
  )
}
