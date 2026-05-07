import { formatCurrency } from './formatCurrency'
import { formatDate } from './formatDate'

async function loadPdfTools() {
  const [{ default: jsPDF }, { default: autoTable }] = await Promise.all([
    import('jspdf'),
    import('jspdf-autotable'),
  ])

  return { jsPDF, autoTable }
}

export async function exportInvoicePdf(invoice) {
  const { jsPDF, autoTable } = await loadPdfTools()
  const doc = new jsPDF()

  doc.setFontSize(16)
  doc.text('Al-Ghani Trading', 105, 15, { align: 'center' })
  doc.setFontSize(12)
  doc.text('Sale Invoice', 105, 23, { align: 'center' })

  doc.setFontSize(10)
  doc.text(`Invoice #: ${invoice.bill_number}`, 14, 35)
  doc.text(`Date: ${formatDate(invoice.date)}`, 14, 42)
  doc.text(`Party: ${invoice.party?.name}`, 14, 49)
  doc.text(`Season: ${invoice.season}`, 140, 35)
  doc.text(`Payment: ${invoice.payment_type}`, 140, 42)

  autoTable(doc, {
    startY: 58,
    head: [['Product', 'Qty', 'Unit', 'Rate (Rs)', 'Total (Rs)']],
    body: invoice.lines?.map((l) => [
      l.product?.name,
      l.quantity,
      l.unit,
      formatCurrency(l.rate_paisas),
      formatCurrency(l.total_paisas),
    ]) || [],
    theme: 'striped',
    headStyles: { fillColor: [21, 128, 61] },
  })

  const finalY = doc.lastAutoTable.finalY + 10
  doc.setFontSize(11)
  doc.text(`Total: ${formatCurrency(invoice.total_paisas)}`, 160, finalY, { align: 'right' })

  doc.save(`invoice-${invoice.bill_number}.pdf`)
}

export async function exportTablePdf(title, columns, rows, filename) {
  const { jsPDF, autoTable } = await loadPdfTools()
  const doc = new jsPDF()
  doc.setFontSize(14)
  doc.text(title, 105, 15, { align: 'center' })

  autoTable(doc, {
    startY: 25,
    head: [columns],
    body: rows,
    theme: 'striped',
    headStyles: { fillColor: [21, 128, 61] },
  })

  doc.save(`${filename}.pdf`)
}
