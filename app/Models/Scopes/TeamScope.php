<?php

namespace App\Models\Scopes;

use Goldoni\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class TeamScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        Nova::whenServing(function (NovaRequest $request) use ($builder, $model): void {
            $user = $request->user();

            if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return;
            }

            $currentId = (int) ($user?->currentTeam()?->value('id') ?? 0);

            $memberIds = $user
                ? $user->teams()->select('teams.id')->pluck('teams.id')->all()
                : [];

            $ownedIds = $user
                ? $user->ownedTeams()->select('teams.id')->pluck('teams.id')->all()
                : [];

            $teamIds = collect([$currentId])
                ->merge($memberIds)
                ->merge($ownedIds)
                ->filter(fn ($id) => (int) $id > 0)
                ->unique()
                ->values();

            if ($teamIds->isEmpty()) {
                $builder->whereRaw('0=1');
                return;
            }

            $builder->whereIn($model->getTable() . '.team_id', $teamIds->all());
        }, function (Request $request) use ($builder): void {
            return;
        });
    }
}
