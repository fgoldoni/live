<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait HasRoleScopes
{
    /** @param Builder<User> $builder */
    #[Scope]
    protected function superAdmins(Builder $builder): void
    {
        $builder->role(self::roleLabel('super_admin'), static::permissionGuard());
    }

    /** @param Builder<User> $builder */
    #[Scope]
    protected function managers(Builder $builder): void
    {
        $builder->role(self::roleLabel('manager'), static::permissionGuard());
    }

    /** @param Builder<User> $builder */
    #[Scope]
    protected function sellers(Builder $builder): void
    {
        $builder->role(self::roleLabel('seller'), static::permissionGuard());
    }

    /** @param Builder<User> $builder */
    #[Scope]
    protected function usersOnly(Builder $builder): void
    {
        $builder->role(self::roleLabel('user'), static::permissionGuard());
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::roleLabel('super_admin'), static::permissionGuard());
    }

    public function isManager(): bool
    {
        return $this->hasRole(self::roleLabel('manager'), static::permissionGuard());
    }

    public function isSeller(): bool
    {
        return $this->hasRole(self::roleLabel('seller'), static::permissionGuard());
    }

    public function isUser(): bool
    {
        return $this->hasRole(self::roleLabel('user'), static::permissionGuard());
    }

    protected static function roleLabel(string $key): string
    {
        $roles = (array) config('model-permissions.roles', []);

        return (string) ($roles[$key] ?? ucfirst(str_replace('_', ' ', $key)));
    }

    /**
     * Source de vérité unique: config/auth.php.
     */
    protected static function permissionGuard(): string
    {
        return (string) config('auth.defaults.guard', 'web');
    }
}
