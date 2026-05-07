import { create } from 'zustand'

let idCounter = 0

export const useNotificationStore = create((set) => ({
  notifications: [],

  add: (message, type = 'success', duration = 4000) => {
    const id = ++idCounter
    set((state) => ({
      notifications: [...state.notifications, { id, message, type }],
    }))
    setTimeout(() => {
      set((state) => ({
        notifications: state.notifications.filter((n) => n.id !== id),
      }))
    }, duration)
  },

  remove: (id) =>
    set((state) => ({
      notifications: state.notifications.filter((n) => n.id !== id),
    })),

  success: (message) => useNotificationStore.getState().add(message, 'success'),
  error:   (message) => useNotificationStore.getState().add(message, 'error', 6000),
  warning: (message) => useNotificationStore.getState().add(message, 'warning'),
  info:    (message) => useNotificationStore.getState().add(message, 'info'),
}))
