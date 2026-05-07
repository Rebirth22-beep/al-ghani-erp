/**
 * ============================================================
 *  AnimatedPage
 * ============================================================
 *
 *  What does it do?
 *  ----------------
 *  Wraps any page content and adds a smooth fade-in + slide-up
 *  animation when the page loads.
 *
 *  Why use it?
 *  -----------
 *  Instead of writing Motion code in every single page file,
 *  you just wrap your page with <AnimatedPage> and it just works.
 *
 *  Usage (super simple):
 *  ---------------------
 *    export function MyPage() {
 *      return (
 *        <AnimatedPage>
 *          <h1>Hello World</h1>
 *        </AnimatedPage>
 *      )
 *    }
 *
 *  Optional: add a delay if you want the animation to start later
 *    <AnimatedPage delay={0.2}>
 * ============================================================
 */

import { motion } from 'motion/react'
import { PAGE_VARIANT, EASE } from '@/utils/animations'

export function AnimatedPage({ children, className = '', delay = 0 }) {
  return (
    <motion.div
      className={className}
      variants={PAGE_VARIANT}
      initial="hidden"
      animate="visible"
      exit="exit"
      transition={{ ...EASE.smooth, delay }}
    >
      {children}
    </motion.div>
  )
}
