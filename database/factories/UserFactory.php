<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }


    public function withRoleKey(string $key): static
    {
        $label = $this->roleLabel($key);

        return $this->afterCreating(function (User $user) use ($label): void {
            $user->assignRole($label);
        });
    }


    public function withRoleLabel(string $label): static
    {
        return $this->afterCreating(function (User $user) use ($label): void {
            $user->assignRole($label);
        });
    }


    public function asSuperAdmin(): static
    {
        return $this->withRoleLabel($this->superAdminLabel());
    }

    public function asManager(): static
    {
        return $this->withRoleLabel($this->roleLabel('manager'));
    }

    public function asSeller(): static
    {
        return $this->withRoleLabel($this->roleLabel('seller'));
    }

    public function asUser(): static
    {
        return $this->withRoleLabel($this->roleLabel('user'));
    }


    private function guardName(): string
    {
        return (string) config('model-permissions.guard_name', config('auth.defaults.guard', 'web'));
    }

    private function superAdminLabel(): string
    {
        return (string) config('model-permissions.super_admin_role', 'Super Admin');
    }

    private function roleLabel(string $key): string
    {
        $roles = (array) config('model-permissions.roles', []);
        return (string) ($roles[$key] ?? $key);
    }
}
