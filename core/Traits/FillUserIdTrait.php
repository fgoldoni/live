<?php

namespace Core\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

trait FillUserIdTrait
{
    public static function bootFillUserIdTrait(): void
    {
        Nova::whenServing(function (NovaRequest $request) {
            if (auth()->check()) {
                static::creating(function ($model) {
                    $model->user_id = auth()->id();
                });
            }
        });
    }
}
