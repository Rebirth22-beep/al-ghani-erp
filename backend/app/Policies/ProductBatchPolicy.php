<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ProductBatch;
use App\Models\User;

class ProductBatchPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant], true);
    }

    public function view(User $user, ProductBatch $batch): bool
    {
        return $this->viewAny($user);
    }
}
