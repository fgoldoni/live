<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Countries\Http\Controllers\CountriesController;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::resource('countries', CountriesController::class)->names('countries');
});
