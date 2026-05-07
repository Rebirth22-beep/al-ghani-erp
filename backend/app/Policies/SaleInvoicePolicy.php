<?php

namespace App\Policies;

use App\Enums\InvoiceStatus;
use App\Enums\UserRole;
use App\Models\SaleInvoice;
use App\Models\User;

class SaleInvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant]);
    }

    public function view(User $user, SaleInvoice $invoice): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman, UserRole::Accountant]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman]);
    }

    public function update(User $user, SaleInvoice $invoice): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman])
            && $invoice->status === InvoiceStatus::Draft;
    }

    public function delete(User $user, SaleInvoice $invoice): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman])
            && $invoice->status === InvoiceStatus::Draft;
    }

    public function post(User $user, SaleInvoice $invoice): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Salesman]);
    }
}
