import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import { useDebounce } from '@/hooks/useDebounce'
import { productService } from '@/services/productService'
import { formatCurrency } from '@/utils/formatCurrency'

export function SearchProductInput({ value, onChange, placeholder = 'Search product...' }) {
  const [search, setSearch] = useState('')
  const [open, setOpen]     = useState(false)
  const debouncedSearch     = useDebounce(search)

  const { data } = useQuery({
    queryKey:  ['product-search', debouncedSearch],
    queryFn:   () => productService.list({ search: debouncedSearch, per_page: 10 }).then((r) => r.data.data),
    enabled:   debouncedSearch.length > 1,
  })

  const handleSelect = (product) => {
    onChange(product)
    setSearch(product.name)
    setOpen(false)
  }

  return (
    <div className="relative">
      <input
        className="form-input"
        placeholder={placeholder}
        value={search || value?.name || ''}
        onChange={(e) => { setSearch(e.target.value); setOpen(true) }}
        onFocus={() => setOpen(true)}
      />
      {open && data?.length > 0 && (
        <ul className="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
          {data.map((product) => (
            <li
              key={product.id}
              onClick={() => handleSelect(product)}
              className="px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer"
            >
              <div className="flex items-center justify-between">
                <span className="font-medium">{product.name}</span>
                <span className="text-gray-500 text-xs">{formatCurrency(product.sale_rate_paisas)}/{product.unit}</span>
              </div>
              {product.batch_number && (
                <span className="text-xs text-gray-400">Batch: {product.batch_number}</span>
              )}
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}
