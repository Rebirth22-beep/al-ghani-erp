import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import { useDebounce } from '@/hooks/useDebounce'
import { customerService } from '@/services/customerService'
import { partnerService } from '@/services/partnerService'
import { supplierService } from '@/services/supplierService'

const PARTY_SEARCH_SERVICES = {
  customer: customerService,
  partner:  partnerService,
  supplier: supplierService,
}

export function SearchPartyInput({ type = 'customer', value, onChange, placeholder = 'Search party...' }) {
  const [search, setSearch] = useState('')
  const [open, setOpen]     = useState(false)
  const debouncedSearch     = useDebounce(search)

  const service = PARTY_SEARCH_SERVICES[type] || customerService

  const { data } = useQuery({
    queryKey:  ['party-search', type, debouncedSearch],
    queryFn:   () => service.list({ search: debouncedSearch, per_page: 10 }).then((r) => r.data.data),
    enabled:   debouncedSearch.length > 1,
  })

  const handleSelect = (party) => {
    onChange(party)
    setSearch(party.name)
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
        <ul className="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
          {data.map((party) => (
            <li
              key={party.id}
              onClick={() => handleSelect(party)}
              className="px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer"
            >
              <span className="font-medium">{party.name}</span>
              {party.phone && <span className="text-gray-400 ml-2 text-xs">{party.phone}</span>}
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}
