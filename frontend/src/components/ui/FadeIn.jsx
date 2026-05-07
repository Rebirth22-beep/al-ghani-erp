/**
 * ============================================================
 *  FadeIn
 * ============================================================
 *
 *  What does it do?
 *  ----------------
 *  Wraps ANY element and makes it fade in + slide up when it
 *  appears on screen. Perfect for cards, headers, sections.
 *
 *  Why use it?
 *  -----------
 *  Beginner-friendly: you don't need to know Motion.
 *  Just wrap your element and it animates automatically.
 *
 *  Usage:
 *  ------
 *    <FadeIn>
 *      <Card>My content</Card>
 *    </FadeIn>
 *
 *    <FadeIn delay={0.2}>
 *      <h2>Appears slightly later</h2>
 *    </FadeIn>
 *
 *  Props:
 *  ------
 *    delay     - how many seconds to wait before animating (default: 0)
 *    direction - 'up' (default), 'down', 'left', 'right'
 *    className - extra CSS classes
 * ============================================================
 */

import { motion } from 'motion/react'
import { FADE_UP_VARIANT, EASE } from '@/utils/animations'

const DIRECTIONS = {
  up:    { y: 16, x: 0 },
  down:  { y: -16, x: 0 },
  left:  { y: 0, x: 16 },
  right: { y: 0, x: -16 },
}

export function FadeIn({ children, delay = 0, direction = 'up', className = '' }) {
  const offset = DIRECTIONS[direction] || DIRECTIONS.up

  const variant = {
    hidden:  { opacity: 0, ...offset },
    visible: { opacity: 1, y: 0, x: 0 },
  }

  return (
    <motion.div
      className={className}
      variants={variant}
      initial="hidden"
      animate="visible"
      transition={{ ...EASE.smooth, delay }}
    >
      {children}
    </motion.div>
  )
}
