<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Goldoni\LaravelTeams\Models\Team;
use Goldoni\ModelPermissions\Policies\BaseModelPolicy;
use Illuminate\Database\Eloquent\Model;
use Override;

final class TeamPolicy extends BaseModelPolicy
{
    protected string $modelClass = Team::class;

    public function before(User $user): ?bool
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
    public function view(Model $user, Model $team): bool
    {
        if ($this->hasPermissionTo($user, 'viewAny')) {
            return true;
        }

        return $this->hasPermissionTo($user, 'view', $team) && $this->userIsOnTeam($user, $team);
    }

    #[Override]
    public function create(Model $model): bool
    {
        if (!$this->hasPermissionTo($model, 'create')) {
            return false;
        }

        $max = (int) config('teams.max_teams_per_user', 0);

        if ($max === 0) {
            return true;
        }

        return $this->userAllTeamsCount($model) < $max;
    }

    #[Override]
    public function update(Model $user, Model $team): bool
    {
        return $this->hasPermissionTo($user, 'update', $team) && $this->userIsOnTeam($user, $team);
    }

    #[Override]
    public function delete(Model $user, Model $team): bool
    {
        return $this->hasPermissionTo($user, 'delete', $team) && $this->userIsOnTeam($user, $team);
    }

    #[Override]
    public function restore(Model $user, Model $team): bool
    {
        return $this->hasPermissionTo($user, 'restore', $team) && $this->userIsOnTeam($user, $team);
    }

    #[Override]
    public function forceDelete(Model $user, Model $team): bool
    {
        return $this->hasPermissionTo($user, 'forceDelete', $team) && $this->userIsOnTeam($user, $team);
    }

    public function manageMembers(Model $user, Model $team): bool
    {
        if ($this->hasPermissionTo($user, 'attachAny') || $this->hasPermissionTo($user, 'detachAny')) {
            return true;
        }

        $canAttach = $this->hasPermissionTo($user, 'attach', $team) && $this->userIsOnTeam($user, $team);
        $canDetach = $this->hasPermissionTo($user, 'detach', $team) && $this->userIsOnTeam($user, $team);

        return $canAttach || $canDetach;
    }

    private function userIsOnTeam(Model $model, Model $team): bool
    {
        return $model instanceof User && $team instanceof Team && $model->isOnTeam($team);
    }

    private function userAllTeamsCount(Model $model): int
    {
        return $model instanceof User ? $model->allTeams()->count() : 0;
    }
}
