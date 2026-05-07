import { AppIcon } from './AppIcon'
import { FadeIn } from './FadeIn'

export function Alert({ type = 'info', title, message, onClose }) {
  const styles = {
    info:    'bg-blue-50 border-blue-200 text-blue-800',
    success: 'bg-green-50 border-green-200 text-green-800',
    warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
    error:   'bg-red-50 border-red-200 text-red-800',
  }

  return (
    <FadeIn direction="right">
      <div className={`rounded-lg border px-4 py-3 flex items-start gap-3 ${styles[type]}`}>
        <div className="flex-1">
          {title && <p className="font-semibold text-sm">{title}</p>}
          {message && <p className="text-sm mt-0.5">{message}</p>}
        </div>
        {onClose && (
          <button
            onClick={onClose}
            className="text-current opacity-60 hover:opacity-100"
            aria-label="Dismiss"
          >
            <AppIcon name="close" size={16} />
          </button>
        )}
      </div>
    </FadeIn>
  )
}
