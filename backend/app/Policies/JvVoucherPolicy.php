<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\JvVoucher;
use App\Models\User;

class JvVoucherPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant], true);
    }

    public function view(User $user, JvVoucher $voucher): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Accountant], true);
    }

    public function delete(User $user, JvVoucher $voucher): bool
    {
        return $user->role === UserRole::Admin;
    }
}
