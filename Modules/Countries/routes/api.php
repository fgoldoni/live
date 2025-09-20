<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Countries\Http\Controllers\CountriesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::apiResource('countries', CountriesController::class)->names('countries');
});
