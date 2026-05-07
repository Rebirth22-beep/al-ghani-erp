import { useLocation, useOutlet } from 'react-router-dom'
import { AnimatePresence, motion } from 'motion/react'
import { Sidebar } from './Sidebar'
import { Topbar } from './Topbar'
import { useSettingsStore } from '@/stores/settingsStore'
import { useNotificationStore } from '@/stores/notificationStore'
import { Alert } from '@/components/ui/Alert'
import { SPRING } from '@/utils/animations'

export function AppShell() {
  const sidebarOpen   = useSettingsStore((s) => s.sidebarOpen)
  const notifications = useNotificationStore((s) => s.notifications)
  const remove        = useNotificationStore((s) => s.remove)
  const location      = useLocation()
  const outlet        = useOutlet()

  return (
    <div className="flex h-screen bg-gray-50 overflow-hidden">
      <Sidebar />

      <motion.div
        className="flex-1 flex flex-col"
        animate={{ marginLeft: sidebarOpen ? 256 : 64 }}
        initial={false}
        transition={SPRING.stiff}
      >
        <Topbar />

        <main className="flex-1 overflow-y-auto p-6">
          <AnimatePresence mode="wait">
            <motion.div
              key={location.pathname}
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -8 }}
              transition={{ duration: 0.25, ease: [0.4, 0, 0.2, 1] }}
            >
              {outlet}
            </motion.div>
          </AnimatePresence>
        </main>
      </motion.div>

      {/* Toast notifications */}
      <div className="fixed bottom-4 right-4 z-50 flex flex-col gap-2 max-w-sm">
        <AnimatePresence initial={false}>
          {notifications.map((n) => (
            <Alert
              key={n.id}
              type={n.type}
              message={n.message}
              onClose={() => remove(n.id)}
            />
          ))}
        </AnimatePresence>
      </div>
    </div>
  )
}
