import { useCallback } from 'react'

export function usePrint() {
  const print = useCallback((elementId) => {
    const element = elementId ? document.getElementById(elementId) : document.body
    if (!element) return

    const printWindow = window.open('', '_blank')
    printWindow.document.write(`
      <html>
        <head>
          <title>Al-Ghani ERP — Print</title>
          <link rel="stylesheet" href="/src/styles/print.css" />
        </head>
        <body>${element.innerHTML}</body>
      </html>
    `)
    printWindow.document.close()
    printWindow.focus()
    printWindow.print()
    printWindow.close()
  }, [])

  return { print }
}
