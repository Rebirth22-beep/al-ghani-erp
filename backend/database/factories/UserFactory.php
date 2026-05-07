<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * Factory for the User model. Defaults to an Admin user; override role
 * in tests with ->state(['role' => UserRole::Salesman]).
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => Hash::make('password'),
            'role'              => UserRole::Admin,
            'is_active'         => true,
            'email_verified_at' => now(),
        ];
    }
}
