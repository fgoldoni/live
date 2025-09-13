<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

trait FillTeamIdTrait
{
    public static function bootFillTeamIdTrait(): void
    {
        Nova::whenServing(function (NovaRequest $request) {
            if (auth()->check()) {
                static::creating(function ($model) {
                    $model->team_id = auth()->user()->currentTeam()->value('id');
                });
            }
        });
    }

    public function scopeActiveTeam(Builder $query, string $table): void
    {
        if (auth()->check()) {
            if (auth()->user()->isSuperAdmin()) {
                $query->withTrashed();
            } else {
                $query->where(
                    $table . '.team_id',
                    auth()->user()->currentTeam()->value('id')
                );
            }
        }
    }
}
