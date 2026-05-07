import { create } from 'zustand'
import { persist } from 'zustand/middleware'

export const useSettingsStore = create(
  persist(
    (set) => ({
      companyName:  'Al-Ghani Trading',
      currency:     'Rs',
      fiscalYear:   null,
      sidebarOpen:  true,

      toggleSidebar: () => set((state) => ({ sidebarOpen: !state.sidebarOpen })),
      setSettings:   (settings) => set(settings),
    }),
    { name: 'alghani-settings' }
  )
)
