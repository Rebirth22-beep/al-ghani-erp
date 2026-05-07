import { useSettingsStore } from '@/stores/settingsStore'
import { useAuth } from '@/hooks/useAuth'
import { Button } from '@/components/ui/Button'

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
        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </Button>

      <div className="flex items-center gap-4">
        <span className="text-sm text-gray-600 capitalize">
          {user?.name} — <span className="font-medium">{user?.role}</span>
        </span>
        <Button
          variant="ghost"
          size="sm"
          onClick={logout}
          className="!text-red-600 hover:!bg-red-50"
        >
          Logout
        </Button>
      </div>
    </header>
  )
}
