<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\WorkerAttendance;

class WorkerAttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant], true);
    }

    public function view(User $user, WorkerAttendance $attendance): bool
    {
        return $this->viewAny($user);
    }
}
