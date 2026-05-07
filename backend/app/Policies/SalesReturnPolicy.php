<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\SalesReturn;
use App\Models\User;

class SalesReturnPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant], true);
    }

    public function view(User $user, SalesReturn $return): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman], true);
    }

    public function delete(User $user, SalesReturn $return): bool
    {
        return $user->role === UserRole::Admin;
    }
}
