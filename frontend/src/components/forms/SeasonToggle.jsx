import { SEASONS, SEASON_LABELS } from '@/constants/seasons'

export function SeasonToggle({ value, onChange }) {
  return (
    <div className="flex rounded-lg border border-gray-300 overflow-hidden">
      {Object.values(SEASONS).map((season) => (
        <button
          key={season}
          type="button"
          onClick={() => onChange(season)}
          className={`px-3 py-1.5 text-sm font-medium transition-colors ${
            value === season
              ? 'bg-brand text-white'
              : 'bg-white text-gray-600 hover:bg-gray-50'
          }`}
        >
          {SEASON_LABELS[season]}
        </button>
      ))}
    </div>
  )
}
