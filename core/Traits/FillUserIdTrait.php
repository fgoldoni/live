<?php

declare(strict_types=1);

namespace Core\Traits;

use Laravel\Nova\Nova;

trait FillUserIdTrait
{
    public static function bootFillUserIdTrait(): void
    {
        Nova::whenServing(function (): void {
            if (auth()->check()) {
                static::creating(function ($model): void {
                    if (is_null($model->user_id)) {
                        $model->user_id = auth()->id();
                    }
                });
            }
        });
    }
}
