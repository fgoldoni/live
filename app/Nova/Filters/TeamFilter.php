<?php

namespace App\Nova\Filters;

use Goldoni\LaravelTeams\Models\Team;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class TeamFilter extends Filter
{
    #[\Override]
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('team_id', $value);
    }

    #[\Override]
    public function options(NovaRequest $request)
    {
        if ($request->user()->isSuperAdmin()) {
            /** @phpstan-ignore-next-line */
            return Team::where('online', true)
                ->orderBy('name')
                ->orderByDesc('created_at')
                ->get()->pluck('id', 'name')->toArray();
        }

        return [];
    }
}
