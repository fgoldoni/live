<?php

namespace App\Nova\Filters;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ManagerFilter extends Filter
{
    public $name = 'Manager';

    public $component = 'select-filter';

    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('manager_id', $value);
    }


    #[\Override]
    public function options(NovaRequest $request): array
    {
        return User::role('manager')
        ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
