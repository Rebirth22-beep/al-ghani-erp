/**
 * ============================================================
 *  Modal
 * ============================================================
 *
 *  What does it do?
 *  ----------------
 *  Shows a popup dialog with smooth fade + scale animations.
 *
 *  Why Motion here?
 *  ----------------
 *  Modals need special "enter/exit" animations (AnimatePresence).
 *  This is one of the few components where we use Motion directly
 *  because it needs to handle appearing and disappearing.
 *
 *  Usage:
 *  ------
 *    <Modal open={isOpen} onClose={closeModal} title="Confirm">
 *      <p>Are you sure?</p>
 *    </Modal>
 * ============================================================
 */

import { useEffect } from 'react'
import { AnimatePresence, motion } from 'motion/react'
import { SCALE_VARIANT, FADE_VARIANT, SPRING } from '@/utils/animations'
import { AppIcon } from './AppIcon'

export function Modal({ open, onClose, title, children, size = 'md', footer }) {
  // Lock body scroll when modal is open
  useEffect(() => {
    document.body.style.overflow = open ? 'hidden' : ''
    return () => { document.body.style.overflow = '' }
  }, [open])

  const sizes = {
    sm:   'max-w-md',
    md:   'max-w-xl',
    lg:   'max-w-3xl',
    xl:   'max-w-5xl',
    full: 'max-w-full mx-4',
  }

  return (
    <AnimatePresence>
      {open && (
        <motion.div
          className="fixed inset-0 z-50 flex items-center justify-center p-4"
          variants={FADE_VARIANT}
          initial="hidden"
          animate="visible"
          exit="hidden"
          transition={{ duration: 0.2 }}
        >
          {/* Clickable backdrop */}
          <div
            className="absolute inset-0 bg-black/50"
            onClick={onClose}
            aria-hidden="true"
          />

          {/* Modal box */}
          <motion.div
            className={`relative w-full ${sizes[size]} bg-white rounded-xl shadow-2xl flex flex-col max-h-[90vh]`}
            variants={SCALE_VARIANT}
            initial="hidden"
            animate="visible"
            exit="hidden"
            transition={SPRING.gentle}
            role="dialog"
            aria-modal="true"
          >
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h2 className="text-lg font-semibold text-gray-900">{title}</h2>
              <button
                onClick={onClose}
                className="text-gray-400 hover:text-gray-600 transition-colors"
                aria-label="Close"
              >
                <AppIcon name="close" size={20} />
              </button>
            </div>

            <div className="flex-1 overflow-y-auto px-6 py-4">{children}</div>

            {footer && (
              <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                {footer}
              </div>
            )}
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  )
}
