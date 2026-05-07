<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Partner;
use App\Models\User;

/**
 * Per SKILL.md §9: Accountant + Admin manage partners. Salesman has no partner access.
 */
class PartnerPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant]);
    }

    public function view(User $user, Partner $partner): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant]);
    }

    public function update(User $user, Partner $partner): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant]);
    }

    public function delete(User $user, Partner $partner): bool
    {
        return $user->role === UserRole::Admin;
    }
}
