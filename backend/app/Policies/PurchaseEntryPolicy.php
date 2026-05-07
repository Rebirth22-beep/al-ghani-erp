<?php

namespace App\Policies;

use App\Enums\InvoiceStatus;
use App\Enums\UserRole;
use App\Models\PurchaseEntry;
use App\Models\User;

/**
 * Per SKILL.md §9: Salesman + Admin manage purchases. Posted entries are immutable.
 */
class PurchaseEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant]);
    }

    public function view(User $user, PurchaseEntry $entry): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman]);
    }

    public function update(User $user, PurchaseEntry $entry): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman])
            && $entry->status === InvoiceStatus::Draft;
    }

    public function delete(User $user, PurchaseEntry $entry): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman])
            && $entry->status === InvoiceStatus::Draft;
    }

    public function post(User $user, PurchaseEntry $entry): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman]);
    }
}
