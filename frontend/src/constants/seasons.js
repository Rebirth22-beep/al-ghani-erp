export const SEASONS = {
  RABI:   'rabi',
  KHARIF: 'kharif',
  NONE:   'none',
}

export const SEASON_LABELS = {
  [SEASONS.RABI]:   'Rabi',
  [SEASONS.KHARIF]: 'Kharif',
  [SEASONS.NONE]:   'None',
}

export const SEASON_OPTIONS = Object.entries(SEASON_LABELS).map(([value, label]) => ({ value, label }))
