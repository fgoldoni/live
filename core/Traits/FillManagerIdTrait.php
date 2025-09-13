<?php

declare(strict_types=1);

namespace Core\Traits;

use Illuminate\Support\Facades\Log;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

trait FillManagerIdTrait
{
    public static function bootFillManagerIdTrait(): void
    {
        Nova::whenServing(function (NovaRequest $request) {
            Log::debug('Nova serving: ' . $request::class);

            if (auth()->check() && auth()->user()->isManager()) {
                static::creating(function ($model) {
                    $managerId = auth()->user()->id;
                    Log::debug('Assigning manager_id', ['manager_id' => $managerId]);
                    $model->manager_id = $managerId;
                });
            }
        });
    }
}
