const ROLE_PERMISSIONS = [
  { role: 'Admin',   permissions: ['All modules', 'User management', 'Settings', 'Reports', 'Delete records'] },
  { role: 'Manager', permissions: ['Sales invoices', 'Purchase entries', 'Returns', 'Customers', 'Suppliers', 'Stock', 'Reports'] },
  { role: 'Cashier', permissions: ['Sales invoices (create)', 'Customer lookup', 'Party payments'] },
  { role: 'Staff',   permissions: ['View sales', 'View stock', 'No delete'] },
]

export function RoleManagementTab() {
  return (
    <div className="space-y-4">
      <p className="text-sm text-gray-500">Role permissions are managed server-side. Below is a read-only overview.</p>
      <div className="overflow-x-auto rounded-xl border border-gray-200">
        <table className="w-full text-sm">
          <thead>
            <tr>
              <th className="table-th w-32">Role</th>
              <th className="table-th">Permissions</th>
            </tr>
          </thead>
          <tbody>
            {ROLE_PERMISSIONS.map((rp) => (
              <tr key={rp.role} className="border-b border-gray-100">
                <td className="table-td font-semibold">{rp.role}</td>
                <td className="table-td">
                  <div className="flex flex-wrap gap-1">
                    {rp.permissions.map((p) => (
                      <span key={p} className="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">
                        {p}
                      </span>
                    ))}
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
