<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class UserScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        Nova::whenServing(function (NovaRequest $request) use ($builder, $model) {
            if (auth()->check()) {
                if (auth()->user()->isSuperAdmin()) {
                    $builder->withTrashed();
                } else {
                    $builder->where(
                        $model->getTable() . '.user_id',
                        auth()->user()->id
                    );
                }
            }
        }, function (Request $request) use ($builder) {
            $builder->newQuery();
        });
    }
}
