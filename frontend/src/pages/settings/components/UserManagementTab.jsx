import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { useUsers, useCreateUser } from '../hooks/useSettings'
import { Table } from '@/components/ui/Table'
import { Button } from '@/components/ui/Button'
import { Modal } from '@/components/ui/Modal'
import { Input } from '@/components/ui/Input'
import { Select } from '@/components/ui/Select'
import { Badge } from '@/components/ui/Badge'
import { ROLES, ROLE_LABELS } from '@/constants/roles'

const schema = z.object({
  name:     z.string().min(1, 'Name is required'),
  email:    z.string().email('Valid email is required'),
  role:     z.string().min(1, 'Role is required'),
  password: z.string().min(6, 'Password must be at least 6 characters'),
})

const ROLE_OPTIONS = [
  { value: ROLES.ADMIN,      label: ROLE_LABELS[ROLES.ADMIN] },
  { value: ROLES.SALESMAN,   label: ROLE_LABELS[ROLES.SALESMAN] },
  { value: ROLES.ACCOUNTANT, label: ROLE_LABELS[ROLES.ACCOUNTANT] },
]

const ROLE_VARIANT = {
  [ROLES.ADMIN]:      'danger',
  [ROLES.SALESMAN]:   'warning',
  [ROLES.ACCOUNTANT]: 'info',
}

export function UserManagementTab() {
  const [modalOpen, setModalOpen] = useState(false)
  const { data, isLoading }       = useUsers()
  const createUser                = useCreateUser()

  const { register, handleSubmit, reset, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
  })

  const columns = [
    { key: 'name',  title: 'Name' },
    { key: 'email', title: 'Email' },
    { key: 'role',  title: 'Role', render: (v) => <Badge variant={ROLE_VARIANT[v] || 'default'}>{v}</Badge> },
  ]

  const onSubmit = (data) => {
    createUser.mutate(data, { onSuccess: () => { setModalOpen(false); reset() } })
  }

  return (
    <div>
      <div className="flex justify-end mb-4">
        <Button onClick={() => setModalOpen(true)}>+ Add User</Button>
      </div>

      <Table columns={columns} data={data?.data} loading={isLoading} />

      <Modal open={modalOpen} onClose={() => { setModalOpen(false); reset() }} title="Add New User">
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <Input
            label="Name"
            required
            placeholder="Full name"
            error={errors.name?.message}
            {...register('name')}
          />
          <Input
            label="Email"
            type="email"
            required
            placeholder="email@example.com"
            error={errors.email?.message}
            {...register('email')}
          />
          <Select
            label="Role"
            required
            options={ROLE_OPTIONS}
            error={errors.role?.message}
            {...register('role')}
          />
          <Input
            label="Password"
            type="password"
            required
            placeholder="Min 6 characters"
            error={errors.password?.message}
            {...register('password')}
          />
          <div className="flex gap-3 justify-end">
            <Button type="button" variant="secondary" onClick={() => { setModalOpen(false); reset() }}>Cancel</Button>
            <Button type="submit" loading={createUser.isPending}>Create User</Button>
          </div>
        </form>
      </Modal>
    </div>
  )
}
