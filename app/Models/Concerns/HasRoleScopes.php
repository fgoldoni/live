<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait HasRoleScopes
{
    #[Scope]
    protected function superAdmins(Builder $builder): void
    {
        $builder->role(self::roleLabel('super_admin'), self::guardName());
    }

    #[Scope]
    protected function managers(Builder $builder): void
    {
        $builder->role(self::roleLabel('manager'), self::guardName());
    }

    #[Scope]
    protected function sellers(Builder $builder): void
    {
        $builder->role(self::roleLabel('seller'), self::guardName());
    }

    #[Scope]
    protected function usersOnly(Builder $builder): void
    {
        $builder->role(self::roleLabel('user'), self::guardName());
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::roleLabel('super_admin'), self::guardName());
    }

    public function isManager(): bool
    {
        return $this->hasRole(self::roleLabel('manager'), self::guardName());
    }

    public function isSeller(): bool
    {
        return $this->hasRole(self::roleLabel('seller'), self::guardName());
    }

    public function isUser(): bool
    {
        return $this->hasRole(self::roleLabel('user'), self::guardName());
    }

    protected static function roleLabel(string $key): string
    {
        $roles = (array) config('model-permissions.roles', []);

        return (string) ($roles[$key] ?? ucfirst(str_replace('_', ' ', $key)));
    }

    protected static function guardName(): string
    {
        return (string) config('model-permissions.guard_name', config('auth.defaults.guard', 'web'));
    }
}
