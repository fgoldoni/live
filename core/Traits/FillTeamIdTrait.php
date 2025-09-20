<?php

declare(strict_types=1);

namespace Core\Traits;

use Laravel\Nova\Nova;

trait FillTeamIdTrait
{
    public static function bootFillTeamIdTrait(): void
    {
        Nova::whenServing(function (): void {
            if (auth()->check()) {
                static::creating(function ($model): void {
                    if (is_null($model->team_id)) {
                        $teamId = auth()->user()?->currentTeam()?->value('id');
                        if (!is_null($teamId)) {
                            $model->team_id = $teamId;
                        }
                    }
                });
            }
        });
    }
}
