<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\StockAlert;
use App\Models\User;

class StockAlertPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman], true);
    }

    public function view(User $user, StockAlert $alert): bool
    {
        return $this->viewAny($user);
    }
}
