<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\StockAdjustment;
use App\Models\User;

/**
 * Per SKILL.md §9: Salesman + Admin can adjust stock. Only Admin can delete adjustment records.
 */
class StockAdjustmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman]);
    }

    public function view(User $user, StockAdjustment $adjustment): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman]);
    }

    public function delete(User $user, StockAdjustment $adjustment): bool
    {
        return $user->role === UserRole::Admin;
    }
}
