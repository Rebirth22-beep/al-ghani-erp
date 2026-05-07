/**
 * ============================================================
 *  ANIMATION PRESETS - Al-Ghani ERP
 * ============================================================
 *
 *  This file stores reusable Motion settings so we do not repeat
 *  timing, easing, and variant values across components.
 *
 *  Beginner rule:
 *  Change animation timing here, not inside page files.
 * ============================================================
 */

/**
 * Spring presets for natural movement.
 * Use springs for sidebar width, modal scale, and similar UI motion.
 */
export const SPRING = {
  stiff:  { type: 'spring', stiffness: 360, damping: 36 },
  gentle: { type: 'spring', stiffness: 200, damping: 24 },
}

/**
 * Timed transitions for simple fades and small movements.
 */
export const EASE = {
  smooth: { duration: 0.35, ease: [0.4, 0, 0.2, 1] },
  fast:   { duration: 0.2,  ease: 'easeOut' },
}

export const PAGE_VARIANT = {
  hidden:  { opacity: 0, y: 12 },
  visible: { opacity: 1, y: 0 },
  exit:    { opacity: 0, y: -8 },
}

export const FADE_UP_VARIANT = {
  hidden:  { opacity: 0, y: 16 },
  visible: { opacity: 1, y: 0 },
}

export const FADE_VARIANT = {
  hidden:  { opacity: 0 },
  visible: { opacity: 1 },
}

export const SCALE_VARIANT = {
  hidden:  { opacity: 0, scale: 0.92 },
  visible: { opacity: 1, scale: 1 },
}

export const SLIDE_RIGHT_VARIANT = {
  hidden:  { opacity: 0, x: 40 },
  visible: { opacity: 1, x: 0 },
}

export const STAGGER_PARENT = {
  hidden: {},
  visible: {
    transition: {
      staggerChildren: 0.06,
      delayChildren: 0.05,
    },
  },
}

export const STAGGER_CHILD = {
  hidden:  { opacity: 0, y: 10 },
  visible: { opacity: 1, y: 0 },
}
