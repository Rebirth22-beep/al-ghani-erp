<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PurchaseReturn;
use App\Models\User;

class PurchaseReturnPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant], true);
    }

    public function view(User $user, PurchaseReturn $return): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman], true);
    }

    public function delete(User $user, PurchaseReturn $return): bool
    {
        return $user->role === UserRole::Admin;
    }
}
