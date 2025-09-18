<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Override;
use Sereny\NovaPermissions\Policies\BasePolicy;

final class UserPolicy extends BasePolicy
{
    /** @var string */
    protected $key = 'user';

    public function before(User $user, string $ability): ?bool
    {
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    #[Override]
    public function viewAny(Model $user): bool
    {
        return $this->hasPermissionTo($user, 'viewAny');
    }

    #[Override]
    public function view(Model $user, $model): bool
    {
        if ($this->hasPermissionTo($user, 'view')) {
            return true;
        }

        return (int) $user->getKey() === (int) $model->getKey();
    }

    #[Override]
    public function create(Model $user): bool
    {
        return $this->hasPermissionTo($user, 'create');
    }

    #[Override]
    public function update(Model $user, $model): bool
    {
        if ($this->hasPermissionTo($user, 'update')) {
            return true;
        }

        return (int) $user->getKey() === (int) $model->getKey();
    }

    #[Override]
    public function delete(Model $user, $model): bool
    {
        return $this->hasPermissionTo($user, 'delete') && (int) $user->getKey() !== (int) $model->getKey();
    }

    #[Override]
    public function restore(Model $user, $model): bool
    {
        return $this->hasPermissionTo($user, 'restore');
    }

    #[Override]
    public function forceDelete(Model $user, $model): bool
    {
        return $this->hasPermissionTo($user, 'forceDelete');
    }
}
