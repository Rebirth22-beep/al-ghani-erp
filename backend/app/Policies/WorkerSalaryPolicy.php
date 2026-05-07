<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\WorkerSalary;

class WorkerSalaryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant], true);
    }

    public function view(User $user, WorkerSalary $salary): bool
    {
        return $this->viewAny($user);
    }
}
