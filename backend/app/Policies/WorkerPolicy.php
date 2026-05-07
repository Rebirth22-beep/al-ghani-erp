<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Worker;

/**
 * Per SKILL.md §9: Worker khata is shared by Admin, Salesman, Accountant.
 * Only Admin can delete worker records.
 */
class WorkerPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Worker $worker): bool { return true; }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant]);
    }

    public function update(User $user, Worker $worker): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant]);
    }

    public function delete(User $user, Worker $worker): bool
    {
        return $user->role === UserRole::Admin;
    }
}
