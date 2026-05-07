import { useState } from 'react'
import { AnimatedPage } from '@/components/ui/AnimatedPage'
import { PageHeader } from '@/components/layout/PageHeader'
import { Card } from '@/components/ui/Card'
import { Tabs } from '@/components/ui/Tabs'
import { UserManagementTab } from './components/UserManagementTab'
import { RoleManagementTab } from './components/RoleManagementTab'
import { CompanySettingsTab } from './components/CompanySettingsTab'
import { FiscalYearTab } from './components/FiscalYearTab'

const TABS = [
  { value: 'users',   label: 'Users' },
  { value: 'roles',   label: 'Roles' },
  { value: 'company', label: 'Company' },
  { value: 'fiscal',  label: 'Fiscal Year' },
]

export function SettingsPage() {
  const [activeTab, setActiveTab] = useState('users')

  return (
    <AnimatedPage>
      <PageHeader title="Settings" subtitle="System configuration and user management" />

      <Card className="!p-0 mb-4">
        <Tabs tabs={TABS} activeTab={activeTab} onChange={setActiveTab} />
      </Card>

      <Card>
        {activeTab === 'users'   && <UserManagementTab />}
        {activeTab === 'roles'   && <RoleManagementTab />}
        {activeTab === 'company' && <CompanySettingsTab />}
        {activeTab === 'fiscal'  && <FiscalYearTab />}
      </Card>
    </AnimatedPage>
  )
}
