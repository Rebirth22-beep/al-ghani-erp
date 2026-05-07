/**
 * ============================================================
 *  AnimatedList
 * ============================================================
 *
 *  What does it do?
 *  ----------------
 *  Renders a list of items with a beautiful stagger animation
 *  (each item fades in one after another).
 *
 *  Why use it?
 *  -----------
 *  Instead of writing complex Motion code for every list,
 *  you just pass your array + render function and this component
 *  handles ALL the animation logic for you.
 *
 *  Usage:
 *  ------
 *    <AnimatedList
 *      items={myArray}
 *      keyExtractor={(item) => item.id}
 *      renderItem={(item) => <div>{item.name}</div>}
 *      className="space-y-2"
 *    />
 *
 *  Props:
 *  ------
 *    items        - the array of data to render
 *    renderItem   - function that returns JSX for each item
 *    keyExtractor - function that returns a unique key for each item
 *    className    - CSS classes for the list container
 *    emptyMessage - text shown when the array is empty
 * ============================================================
 */

import { motion } from 'motion/react'
import { STAGGER_PARENT, STAGGER_CHILD, EASE } from '@/utils/animations'

export function AnimatedList({
  items = [],
  renderItem,
  keyExtractor,
  className = '',
  emptyMessage = 'No items found.',
}) {
  if (!items.length) {
    return <p className="text-sm text-gray-400 py-2">{emptyMessage}</p>
  }

  return (
    <motion.ul
      className={className}
      variants={STAGGER_PARENT}
      initial="hidden"
      animate="visible"
    >
      {items.map((item, index) => (
        <motion.li
          key={keyExtractor ? keyExtractor(item) : index}
          variants={STAGGER_CHILD}
          transition={EASE.fast}
        >
          {renderItem(item)}
        </motion.li>
      ))}
    </motion.ul>
  )
}
