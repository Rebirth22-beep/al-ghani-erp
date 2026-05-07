/**
 * ============================================================
 *  WidgetError - error state with a retry button
 * ============================================================
 *
 *  Use this whenever a data-fetching widget fails. It shows a clear
 *  error message with a Retry button. NEVER replace failed data
 *  with fake numbers (rule from project-rules-and-decisions.md Section 21).
 *
 *  Usage:
 *    {query.isError && (
 *      <WidgetError
 *        message={query.error?.message}
 *        onRetry={() => query.refetch()}
 *      />
 *    )}
 *
 *  Props:
 *    - message  string  Optional error message to display
 *    - onRetry  ()=>any Optional click handler for the Retry button
 * ============================================================
 */

import { Button } from './Button'
import { AppIcon } from './AppIcon'

export function WidgetError({ message = 'Could not load data.', onRetry }) {
  return (
    <div className="flex flex-col items-center justify-center gap-3 py-8 text-center">
      <AppIcon name="alert" size={28} className="text-red-600" />
      <p className="text-sm text-gray-700 max-w-xs">{message}</p>
      {onRetry && (
        <Button variant="secondary" size="sm" onClick={onRetry}>
          Retry
        </Button>
      )}
    </div>
  )
}
