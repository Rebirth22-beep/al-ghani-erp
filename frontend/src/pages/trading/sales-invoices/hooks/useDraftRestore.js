import { useLocalStorage } from '@/hooks/useLocalStorage'

const DRAFT_KEY = 'sale-invoice-draft'

export function useDraftRestore() {
  const [draft, setDraft, clearDraft] = useLocalStorage(DRAFT_KEY, null)

  const saveDraft = (data) => setDraft({ ...data, savedAt: new Date().toISOString() })

  return { draft, saveDraft, clearDraft }
}
