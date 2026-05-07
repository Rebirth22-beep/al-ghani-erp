import { useSettingsStore } from '@/stores/settingsStore'
import { useAuth } from '@/hooks/useAuth'
import { Button } from '@/components/ui/Button'
import { AppIcon } from '@/components/ui/AppIcon'

export function Topbar() {
  const toggleSidebar = useSettingsStore((s) => s.toggleSidebar)
  const { user, logout } = useAuth()

  return (
    <header className="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
      <Button
        variant="ghost"
        size="sm"
        onClick={toggleSidebar}
        aria-label="Toggle sidebar"
        className="!p-2"
      >
        <AppIcon name="menu" size={20} />
      </Button>

      <div className="flex items-center gap-4">
        <span className="text-sm text-gray-600 capitalize">
          {user?.name} — <span className="font-medium">{user?.role}</span>
        </span>
        <Button
          variant="ghost"
          size="sm"
          onClick={logout}
          className="!text-red-600 hover:!bg-red-50 gap-1.5"
        >
          <AppIcon name="logout" size={16} />
          Logout
        </Button>
      </div>
    </header>
  )
}
