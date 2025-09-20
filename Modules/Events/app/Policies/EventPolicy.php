<?php

declare(strict_types=1);

namespace Modules\Events\Policies;

use Goldoni\LaravelTeams\Enums\TeamRoleEnum;
use Goldoni\LaravelTeams\Models\Team;
use Goldoni\ModelPermissions\Policies\BaseModelPolicy;
use Illuminate\Database\Eloquent\Model;
use Modules\Events\Models\Event;
use Override;

final class EventPolicy extends BaseModelPolicy
{
    protected string $modelClass = Event::class;

    #[Override]
    public function viewAny(Model $user): bool
    {
        return $this->hasPermissionTo($user, 'viewAny');
    }

    #[Override]
    public function view(Model $user, Model $event): bool
    {

        if (! $this->hasPermissionTo($user, 'view', $event)) {
            return false;
        }

        $team = $this->eventTeam($event);

        if (! $team) {
            return false;
        }

        return $user->hasTeamRoleAtLeast($team, TeamRoleEnum::VIEWER);
    }

    #[Override]
    public function create(Model $user): bool
    {
        if (! $this->hasPermissionTo($user, 'create')) {
            return false;
        }

        $team = $user->currentTeam()?->first();

        if (! $team) {
            return false;
        }

        return $user->hasTeamRoleAtLeast($team, TeamRoleEnum::ADMIN);
    }

    #[Override]
    public function update(Model $user, Model $event): bool
    {
        if (! $this->hasPermissionTo($user, 'update', $event)) {
            return false;
        }

        $team = $this->eventTeam($event);

        if (! $team) {
            return false;
        }

        return $user->hasTeamRoleAtLeast($team, TeamRoleEnum::ADMIN);
    }

    #[Override]
    public function delete(Model $user, Model $event): bool
    {
        if (! $this->hasPermissionTo($user, 'delete', $event)) {
            return false;
        }

        $team = $this->eventTeam($event);

        if (! $team) {
            return false;
        }

        return $user->hasTeamRoleAtLeast($team, TeamRoleEnum::ADMIN);
    }

    #[Override]
    public function deleteAny(Model $user): bool
    {
        if (! $this->hasPermissionTo($user, 'deleteAny')) {
            return false;
        }

        $team = $user->currentTeam()?->first();

        if (! $team) {
            return false;
        }

        return $user->hasTeamRoleAtLeast($team, TeamRoleEnum::ADMIN);
    }

    #[Override]
    public function restore(Model $user, Model $event): bool
    {
        return $this->hasPermissionTo($user, 'restore', $event);
    }

    #[Override]
    public function restoreAny(Model $user): bool
    {
        return $this->hasPermissionTo($user, 'restoreAny');
    }

    #[Override]
    public function forceDelete(Model $user, Model $event): bool
    {
        return $this->hasPermissionTo($user, 'forceDelete', $event);
    }

    #[Override]
    public function forceDeleteAny(Model $user): bool
    {
        return $this->hasPermissionTo($user, 'forceDeleteAny');
    }

    private function eventTeam(Model $event): ?Team
    {
        $teamId = (int) ($event->team_id ?? 0);

        if ($teamId <= 0) {
            return null;
        }

        return Team::query()->find($teamId);
    }
}
