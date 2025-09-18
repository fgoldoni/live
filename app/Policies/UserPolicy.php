<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Goldoni\ModelPermissions\Policies\BaseModelPolicy;
use Illuminate\Database\Eloquent\Model;
use Override;

final class UserPolicy extends BaseModelPolicy
{
    protected string $modelClass = User::class;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    #[Override]
    public function viewAny(Model $model): bool
    {
        return $this->hasPermissionTo($model, 'viewAny');
    }

    #[Override]
    public function view(Model $user, Model $model): bool
    {
        if ($this->hasPermissionTo($user, 'view', $model)) {
            return true;
        }

        return (int) $user->getKey() === (int) $model->getKey();
    }

    #[Override]
    public function create(Model $model): bool
    {
        return $this->hasPermissionTo($model, 'create');
    }

    #[Override]
    public function update(Model $user, Model $model): bool
    {
        if ($this->hasPermissionTo($user, 'update', $model)) {
            return true;
        }

        return (int) $user->getKey() === (int) $model->getKey();
    }

    #[Override]
    public function delete(Model $user, Model $model): bool
    {
        return $this->hasPermissionTo($user, 'delete', $model) && (int) $user->getKey() !== (int) $model->getKey();
    }

    #[Override]
    public function restore(Model $user, Model $model): bool
    {
        return $this->hasPermissionTo($user, 'restore', $model);
    }

    #[Override]
    public function forceDelete(Model $user, Model $model): bool
    {
        return $this->hasPermissionTo($user, 'forceDelete', $model);
    }
}
