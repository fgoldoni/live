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
        if (! $this->hasPermissionTo($model, 'create')) {
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
        if ($this->isOwnerOrAdmin($user, $team)) {
            return true;
        }

        if ($this->hasPermissionTo($user, 'attachAny') || $this->hasPermissionTo($user, 'detachAny')) {
            return true;
        }

        $canAttach = $this->hasPermissionTo($user, 'attach', $team) && $this->userIsOnTeam($user, $team);
        $canDetach = $this->hasPermissionTo($user, 'detach', $team) && $this->userIsOnTeam($user, $team);

        return $canAttach || $canDetach;
    }

    public function transferOwnership(Model $user, Model $team): bool
    {
        if (! $this->userIsOnTeam($user, $team)) {
            return false;
        }

        if ($this->isOwnerOrAdmin($user, $team)) {
            return true;
        }

        return $this->hasPermissionTo($user, 'transferOwnership', $team);
    }

    public function invite(Model $user, Model $team): bool
    {
        if (! $this->userIsOnTeam($user, $team)) {
            return false;
        }

        if ($this->isOwnerOrAdmin($user, $team)) {
            return true;
        }

        return $this->hasPermissionTo($user, 'invite', $team);
    }

    public function leave(Model $user, Model $team): bool
    {
        if (! $this->userIsOnTeam($user, $team)) {
            return false;
        }

        if ($this->isOwner($user, $team)) {
            return false;
        }

        return $this->hasPermissionTo($user, 'leave', $team);
    }

    public function acceptInvite(Model $user, Model $team): bool
    {
        return $this->hasPermissionTo($user, 'acceptInvite', $team);
    }

    public function declineInvite(Model $user, Model $team): bool
    {
        return $this->hasPermissionTo($user, 'declineInvite', $team);
    }

    private function userIsOnTeam(Model $model, Model $team): bool
    {
        return $model instanceof User && $team instanceof Team && $model->isOnTeam($team);
    }

    private function userAllTeamsCount(Model $model): int
    {
        return $model instanceof User ? $model->allTeams()->count() : 0;
    }

    private function isOwner(Model $model, Model $team): bool
    {
        return $model instanceof User && $team instanceof Team && $model->ownsTeam($team);
    }

    private function isOwnerOrAdmin(Model $model, Model $team): bool
    {
        if (! ($model instanceof User && $team instanceof Team)) {
            return false;
        }

        if ($model->ownsTeam($team)) {
            return true;
        }

        if (method_exists($model, 'hasTeamRoleAdmin')) {
            return $model->hasTeamRoleAdmin($team);
        }

        return false;
    }
}
