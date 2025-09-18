<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Override;
use Sereny\NovaPermissions\Policies\BasePolicy;

final class TeamPolicy extends BasePolicy
{
    /** @var string */
    protected $key = 'team';

    public function before(User $user): ?bool
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
    public function view(Model $user, $team): bool
    {
        if ($this->hasPermissionTo($user, 'viewAny')) {
            return true;
        }

        return $this->hasPermissionTo($user, 'view') && $user->isOnTeam($team);
    }

    #[Override]
    public function create(Model $user): bool
    {
        if (! $this->hasPermissionTo($user, 'create')) {
            return false;
        }

        $max = (int) config('teams.max_teams_per_user', 0);

        if ($max === 0) {
            return true;
        }

        return $user->allTeams()->count() < $max;
    }

    #[Override]
    public function update(Model $user, $team): bool
    {
        return $this->hasPermissionTo($user, 'update') && $user->isOnTeam($team);
    }

    #[Override]
    public function delete(Model $user, $team): bool
    {
        return $this->hasPermissionTo($user, 'delete') && $user->isOnTeam($team);
    }

    #[Override]
    public function restore(Model $user, $team): bool
    {
        return $this->hasPermissionTo($user, 'restore') && $user->isOnTeam($team);
    }

    #[Override]
    public function forceDelete(Model $user, $team): bool
    {
        return $this->hasPermissionTo($user, 'forceDelete') && $user->isOnTeam($team);
    }

    public function manageMembers(Model $model, $team): bool
    {
        if ($this->hasPermissionTo($model, 'attachAny') || $this->hasPermissionTo($model, 'detachAny')) {
            return true;
        }

        $canAttach = $this->hasPermissionTo($model, 'attach') && $model->isOnTeam($team);
        $canDetach = $this->hasPermissionTo($model, 'detach') && $model->isOnTeam($team);

        return $canAttach || $canDetach;
    }
}
